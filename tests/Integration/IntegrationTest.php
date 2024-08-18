<?php

namespace Wednesdev\Mail\Tests\Integration;

use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase;
use Wednesdev\Mail\Facades\Email;
use Wednesdev\Mail\Models\EmailTemplate;
use Wednesdev\Mail\Providers\MailServiceProvider;

class IntegrationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MailServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        Artisan::call('migrate', ['--database' => 'testing']);

        // Create a test email template
        EmailTemplate::create([
            'name' => 'test_template',
            'description' => 'Test email template',
        ])->translations()->create([
            'locale' => 'en',
            'subject' => 'Test Subject {name}',
            'body' => 'Test Body {name}',
        ]);
    }

    public function testEmailSending()
    {
        $mailable = new class {
            public function emails()
            {
                return new class {
                    public function save($email)
                    {
                        return $email;
                    }
                };
            }
        };

        $result = Email::send($mailable, 'test_template', 'test@example.com', ['name' => 'John'], 'en');

        $this->assertDatabaseHas('emails', [
            'recipient_email' => 'test@example.com',
            'status' => 1, // Assuming 1 is the status for sent
        ]);

        $this->assertEquals('Test Subject John', $result->subject);
        $this->assertEquals('Test Body John', $result->body);
    }
}