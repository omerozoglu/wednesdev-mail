<?php

namespace Wednesdev\Mail\Tests\Unit;

use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Event;
use Mockery;
use Wednesdev\Mail\Events\EmailFailed;
use Wednesdev\Mail\Events\EmailSent;
use Wednesdev\Mail\Models\Email;
use Wednesdev\Mail\Models\EmailTemplate;
use Wednesdev\Mail\Models\EmailTemplateTranslation;
use Wednesdev\Mail\Services\EmailService;
use Wednesdev\Mail\Tests\TestCase;

class EmailServiceTest extends TestCase
{
    protected $mailer;
    protected $emailService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mailer = Mockery::mock(Mailer::class);
        $this->emailService = new EmailService($this->mailer);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testSendMethodSuccess()
    {
        // Arrange
        $mailable = Mockery::mock();
        $mailable->shouldReceive('emails->save')->once()->andReturnUsing(function ($email) {
            return true;
        });

        $template = EmailTemplate::factory()->create(['name' => 'test_template']);
        EmailTemplateTranslation::factory()->create([
            'email_template_id' => $template->id,
            'locale' => 'en',
            'subject' => 'Test Subject {name}',
            'body' => 'Test Body {name}'
        ]);

        $this->mailer->shouldReceive('send')
            ->once()
            ->andReturnUsing(function ($view, $data, $callback) {
                $message = Mockery::mock();
                $message->shouldReceive('to')->with('test@example.com')->andReturn($message);
                $message->shouldReceive('subject')->with('Test Subject John')->andReturn($message);
                $message->shouldReceive('html')->with('Test Body John')->andReturn($message);
                $callback($message);
            });

        Event::fake();

        // Act
        $result = $this->emailService->send($mailable, 'test_template', 'test@example.com', ['name' => 'John'], 'en');

        // Assert
        $this->assertInstanceOf(Email::class, $result);
        $this->assertEquals(1, $result->status, 'Email status should be 1 (Sent)');
        $this->assertNotNull($result->sent_at, 'sent_at should not be null');

        Event::assertDispatched(EmailSent::class);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testSendMethodFailure()
    {
        // Arrange
        $mailable = Mockery::mock();
        $mailable->shouldReceive('emails->save')->once()->andReturn(true);

        $template = EmailTemplate::factory()->create(['name' => 'test_template']);
        EmailTemplateTranslation::factory()->create([
            'email_template_id' => $template->id,
            'locale' => 'en',
            'subject' => 'Test Subject',
            'body' => 'Test Body'
        ]);

        $this->mailer->shouldReceive('send')
            ->once()
            ->andThrow(new Exception('Sending failed'));

        Event::fake();

        // Act & Assert
        $this->expectException(Exception::class);

        try {
            $this->emailService->send($mailable, 'test_template', 'test@example.com', [], 'en');
        } catch (Exception $e) {
            $this->assertEquals('Sending failed', $e->getMessage());

            Event::assertDispatched(EmailFailed::class);

            $email = Email::first();
            $this->assertEquals(2, $email->status);

            throw $e;
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testSendMethodWithNonExistentTemplate()
    {
        // Arrange
        $mailable = Mockery::mock();

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->emailService->send($mailable, 'non_existent_template', 'test@example.com', [], 'en');
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testSendMethodWithNonExistentTranslation()
    {
        // Arrange
        $mailable = Mockery::mock();
        $mailable->shouldReceive('emails->save')->once()->andReturn(true);

        $template = EmailTemplate::factory()->create(['name' => 'test_template']);
        // No translation created for 'en' locale

        // Act & Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->emailService->send($mailable, 'test_template', 'test@example.com', [], 'en');
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}