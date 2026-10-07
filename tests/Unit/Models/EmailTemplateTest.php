<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

it('creates a template with all fields', function () {
    $template = EmailTemplate::create([
        'name' => 'Welcome Email',
        'slug' => 'welcome-email',
        'subject' => 'Welcome, {{name}}!',
        'preheader' => 'Thanks for joining',
        'blocks' => [
            ['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Hello {{name}}']],
        ],
        'settings' => ['primary_color' => '#378ADD'],
        'category' => 'transactional',
        'is_active' => true,
    ]);

    expect($template->name)->toBe('Welcome Email');
    expect($template->blocks)->toBeArray()->toHaveCount(1);
    expect($template->settings)->toBeArray()->toHaveKey('primary_color');
    expect($template->is_active)->toBeTrue();
});

it('casts blocks and settings to arrays', function () {
    $template = EmailTemplate::create([
        'name' => 'Test',
        'slug' => 'test',
        'subject' => 'Test',
        'blocks' => [],
        'settings' => [],
    ]);

    $template->refresh();

    expect($template->blocks)->toBeArray();
    expect($template->settings)->toBeArray();
});

it('scopes active templates', function () {
    EmailTemplate::create(['name' => 'Active', 'slug' => 'active', 'subject' => 'S', 'blocks' => [], 'is_active' => true]);
    EmailTemplate::create(['name' => 'Inactive', 'slug' => 'inactive', 'subject' => 'S', 'blocks' => [], 'is_active' => false]);

    expect(EmailTemplate::active()->count())->toBe(1);
    expect(EmailTemplate::active()->first()->name)->toBe('Active');
});

it('replaces variables in render', function () {
    $template = EmailTemplate::create([
        'name' => 'Var Test',
        'slug' => 'var-test',
        'subject' => 'Hello {{name}}',
        'blocks' => [
            ['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Welcome {{name}} from {{company}}']],
        ],
    ]);

    $html = $template->render(['name' => 'John', 'company' => 'Acme']);

    expect($html)
        ->toContain('Welcome John from Acme')
        ->not->toContain('{{name}}');
});

it('supports soft deletes', function () {
    $template = EmailTemplate::create([
        'name' => 'To Delete',
        'slug' => 'to-delete',
        'subject' => 'Subject',
        'blocks' => [],
    ]);

    $template->delete();

    expect(EmailTemplate::count())->toBe(0);
    expect(EmailTemplate::withTrashed()->count())->toBe(1);
});
