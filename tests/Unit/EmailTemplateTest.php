<?php

use Wednesdev\Mail\Models\EmailTemplate;
use Wednesdev\Mail\Models\EmailTemplateTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create email template', function () {
    $template = EmailTemplate::factory()->create();

    $this->assertModelExists($template);
    $this->assertDatabaseHas('email_templates', [
        'id' => $template->id,
        'name' => $template->name,
    ]);
});

test('email template has a name', function () {
    $template = EmailTemplate::factory()->create(['name' => 'welcome_email']);

    expect($template->name)->toBe('welcome_email');
});

test('email template has an optional description', function () {
    $template = EmailTemplate::factory()->create(['description' => 'A welcome email for new users']);

    expect($template->description)->toBe('A welcome email for new users');
});

test('can update email template', function () {
    $template = EmailTemplate::factory()->create();

    $template->update(['name' => 'updated_template']);

    $this->assertDatabaseHas('email_templates', [
        'id' => $template->id,
        'name' => 'updated_template',
    ]);
});

test('can delete email template', function () {
    $template = EmailTemplate::factory()->create();

    $template->delete();

    $this->assertSoftDeleted($template);
});