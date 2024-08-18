<?php

namespace Wednesdev\Mail\Providers;

use Illuminate\Support\ServiceProvider;
use Wednesdev\Mail\Commands\EmailPackageCommand;
use Wednesdev\Mail\Services\EmailService;

class MailServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('email', function ($app) {
            return new EmailService($app['mailer']);
        });
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Publish seeder
        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'seeders');

        // Register the seeder
        if ($this->app->runningInConsole()) {
            $this->commands([
                EmailPackageCommand::class,
            ]);
        }
    }
}