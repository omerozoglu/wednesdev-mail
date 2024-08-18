# Laravel Email Package

⚠️ **IMPORTANT: This package is currently under active development and is not yet ready for production use. Features may be incomplete or subject to change.** ⚠️

A highly extendable and efficient email package for Laravel 11 projects.

## Development Status

This package is in active development. While we're working hard to complete and stabilize all features, please be aware that:

- Some functionalities may be incomplete or change significantly.
- The API is not yet stable and may undergo revisions.
- Documentation may be incomplete or outdated in places.
- There might be unforeseen bugs or issues.

We appreciate your interest and patience. If you'd like to contribute or provide feedback, please check the "Contributing" section below.

## Description

This package aims to simplify and enhance email sending capabilities in Laravel 11 applications. It provides a flexible system for managing email templates, translations, and sending emails with ease.

## Planned Features

- Email template management with database storage
- Multi-language support for email templates
- Easy-to-use facade for sending emails
- Integration with Laravel's queueing system for efficient email dispatch
- Customizable email attachments
- Comprehensive dashboard for detailed logs and statistics:
    - Visual representation of email sending patterns
    - Success and failure rates
    - Delivery time analytics
    - Recipient engagement metrics (opens, clicks, etc.)
- Artisan commands for easy creation of email templates:
    - Stub-based generation of template files
    - Interactive CLI for template customization
- Admin panel for email template management:
    - CRUD operations for email templates
    - Live preview of templates
    - Version control and rollback capabilities for templates
    - Access control and user permissions for template management

## Installation

As this package is still under development, it's not recommended for production use. However, if you'd like to try it out or contribute, you can install it via composer:

```bash
composer require wednesdev/mail
```

## Configuration

After installation, publish the package configuration file (note that this might change as development progresses):

```bash
php artisan vendor:publish --provider="Wednesdev\Mail\Providers\MailServiceProvider"
```

## Usage

Please note that the usage examples below are subject to change as development progresses.

### Setting up email templates

```php
use Wednesdev\Mail\Models\EmailTemplate;

$template = EmailTemplate::create([
    'name' => 'welcome_email',
    'description' => 'Welcome email for new users',
]);

$template->translations()->create([
    'locale' => 'en',
    'subject' => 'Welcome to Our Platform, {name}!',
    'body' => '<h1>Welcome, {name}!</h1><p>We're excited to have you on board.</p>',
]);
```

### Sending an email

```php
use Wednesdev\Mail\Facades\Email;

Email::send($user, 'welcome_email', 'user@example.com', ['name' => 'John Doe'], 'en');
```

## Testing

To run the package tests (which are also under development):

```bash
composer test
```

## Contributing

We welcome contributions! As the package is still in development, there are many opportunities to help. Please feel free to submit issues, feature requests, or pull requests.

## License

This package is open-sourced software licensed under the MIT license.