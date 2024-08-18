<?php
/*
namespace Wednesdev\Mail\Tests\Integration;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;
use Wednesdev\Mail\Facades\Email;
use Wednesdev\Mail\Models\EmailTemplate;
use Wednesdev\Mail\Providers\MailServiceProvider;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [MailServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Explicitly run migrations
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        // Debug: List all tables in the database
        $tables = $this->getTablesInDatabase();
        echo "Tables in database: " . implode(', ', $tables) . "\n";

        // Verify that the tables were created
        $emailTemplatesExists = Schema::hasTable('email_templates');
        $emailTemplateTranslationsExists = Schema::hasTable('email_template_translations');
        $emailsExists = Schema::hasTable('emails');

        echo "email_templates exists: " . ($emailTemplatesExists ? 'Yes' : 'No') . "\n";
        echo "email_template_translations exists: " . ($emailTemplateTranslationsExists ? 'Yes' : 'No') . "\n";
        echo "emails exists: " . ($emailsExists ? 'Yes' : 'No') . "\n";

        $this->assertTrue($emailTemplatesExists, 'email_templates table does not exist');
        $this->assertTrue($emailTemplateTranslationsExists, 'email_template_translations table does not exist');
        $this->assertTrue($emailsExists, 'emails table does not exist');

        // Create a test email template
        if ($emailTemplatesExists && $emailTemplateTranslationsExists) {
            EmailTemplate::create([
                'name' => 'test_template',
                'description' => 'Test email template',
            ])->translations()->create([
                'locale' => 'en',
                'subject' => 'Test Subject {name}',
                'body' => 'Test Body {name}',
            ]);
        } else {
            echo "Skipping email template creation due to missing tables.\n";
        }
    }

    protected function getTablesInDatabase()
    {
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
        return array_map(function($table) {
            return $table->name;
        }, $tables);
    }

    public function testEmailSending()
    {
        // Debug: Check if the email template was created
        $template = EmailTemplate::where('name', 'test_template')->first();
        if ($template) {
            echo "Test template found: " . $template->name . "\n";
        } else {
            echo "Test template not found in database.\n";
        }

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
}*/