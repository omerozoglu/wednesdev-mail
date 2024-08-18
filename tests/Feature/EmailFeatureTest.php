<?php

namespace Wednesdev\Mail\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Wednesdev\Mail\Events\EmailSent;
use Wednesdev\Mail\Facades\Email;
use Wednesdev\Mail\Models\EmailTemplate;
use Wednesdev\Mail\Models\EmailTemplateTranslation;
use Wednesdev\Mail\Tests\TestCase;

class EmailFeatureTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations
        $this->artisan('wednesdev:setup', ['--migrate' => true]);

        // Create a test email template
        $template = EmailTemplate::create([
            'name' => 'welcome_email',
            'description' => 'Welcome email for new users',
        ]);

        EmailTemplateTranslation::create([
            'email_template_id' => $template->id,
            'locale' => 'en',
            'subject' => 'Welcome to Our Platform, {name}!',
            'body' => '<h1>Welcome, {name}!</h1><p>We\'re excited to have you on board.</p>',
        ]);
    }

    /**
     * @return void
     */
    public function testCreateTemplateAndSendEmail()
    {
        // Fake the Mail facade
        Mail::fake();

        // Fake the event dispatcher
        Event::fake();

        // Create a mailable mock (this would typically be a real model in your application)
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

        // Send an email using your package
        $result = Email::send($mailable, 'welcome_email', 'test@example.com', ['name' => 'John Doe'], 'en');

        // Assert that the email was "sent"
        Mail::assertSent(function ($mail) {
            return $mail->hasTo('test@example.com') &&
                $mail->subject == 'Welcome to Our Platform, John Doe!' &&
                str_contains($mail->html, 'Welcome, John Doe!');
        });

        // Assert that the EmailSent event was dispatched
        Event::assertDispatched(EmailSent::class);

        // Assert that the result is an Email model instance
        $this->assertInstanceOf(\Wednesdev\Mail\Models\Email::class, $result);

        // Assert that the email was saved to the database
        $this->assertDatabaseHas('emails', [
            'recipient_email' => 'test@example.com',
            'status' => 1, // Assuming 1 is the status for sent
        ]);

        // Assert that the placeholders were replaced in the email content
        $this->assertEquals('Welcome to Our Platform, John Doe!', $result->subject);
        $this->assertStringContainsString('Welcome, John Doe!', $result->body);
    }

    /**
     * @return void
     */
    public function testSendingEmailWithNonExistentTemplate()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

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

        Email::send($mailable, 'non_existent_template', 'test@example.com', [], 'en');
    }

    /**
     * @return void
     */
    public function testSendingEmailWithNonExistentLocale()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

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

        Email::send($mailable, 'welcome_email', 'test@example.com', [], 'fr'); // Assuming 'fr' translation doesn't exist
    }
}