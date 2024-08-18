<?php

namespace Wednesdev\Mail\Commands;

use Illuminate\Console\Command;
use Wednesdev\Mail\Database\Seeders\EmailDatabaseSeeder;

class EmailPackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wednesdev:setup 
                            {--migrate : Run the database migrations}
                            {--seed : Seed the email templates}
                            {--force : Force the operation to run in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup and manage the email package';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->option('migrate')) {
            $this->runMigrations();
        }

        if ($this->option('seed')) {
            $this->seedEmailTemplates();
        }

        if (!$this->option('migrate') && !$this->option('seed')) {
            $this->info('No action specified. Use --migrate to run migrations or --seed to seed email templates.');
        }

        // Future options can be added here
    }

    protected function runMigrations()
    {
        $this->info('Running email package migrations...');
        $params = ['--path' => 'vendor/wednesdev/mail/database/migrations'];

        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $result = $this->call('migrate', $params);

        if ($result === 0) {
            $this->info('Email package migrations completed successfully.');
        } else {
            $this->error('Email package migrations failed.');
        }
    }

    protected function seedEmailTemplates()
    {
        $this->info('Seeding email templates...');
        $params = ['--class' => EmailDatabaseSeeder::class];

        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $result = $this->call('db:seed', $params);

        if ($result === 0) {
            $this->info('Email templates seeded successfully.');
        } else {
            $this->error('Seeding email templates failed.');
        }
    }
}
