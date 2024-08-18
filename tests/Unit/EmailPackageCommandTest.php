<?php

namespace Wednesdev\Mail\Tests\Unit;

use Illuminate\Support\Facades\DB;
use Wednesdev\Mail\Tests\TestCase;

class EmailPackageCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'testing']);
    }

    /**
     * @return void
     */
    public function testCommandRunsWithoutOptions()
    {
        $this->artisan('wednesdev:setup')
            ->expectsOutput('No action specified. Use --migrate to run migrations or --seed to seed email templates.')
            ->assertExitCode(0);
    }

    /**
     * @return void
     */
    public function testSeedOption()
    {
        // First, run migrations to create the necessary tables
        $this->artisan('wednesdev:setup', ['--migrate' => true]);

        // Now run the seed command
        $this->artisan('wednesdev:setup', ['--seed' => true])
            ->expectsOutput('Seeding email templates...')
            ->expectsOutput('Email templates seeded successfully.')
            ->assertExitCode(0);

        // Check if the email_templates table has been seeded
        $this->assertDatabaseHas('email_templates', [
            'name' => 'welcome_email'
        ]);
    }

    /**
     * @return void
     */
    public function testMigrateAndSeedOptions()
    {
        $this->artisan('wednesdev:setup', ['--migrate' => true, '--seed' => true])
            ->expectsOutput('Running email package migrations...')
            ->expectsOutput('Email package migrations completed successfully.')
            ->expectsOutput('Seeding email templates...')
            ->expectsOutput('Email templates seeded successfully.')
            ->assertExitCode(0);

        // Check if tables exist and have been seeded
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('email_templates'));
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('emails'));
        $this->assertDatabaseHas('email_templates', [
            'name' => 'welcome_email'
        ]);
    }
}