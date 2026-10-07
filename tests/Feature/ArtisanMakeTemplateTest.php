<?php

use JeffersonGoncalves\MailEditor\Enums\TemplateCategory;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

it('creates a template via artisan command', function () {
    $this->artisan('mail-editor:make-template', ['name' => 'Welcome Email'])
        ->assertExitCode(0);

    $template = EmailTemplate::where('slug', 'welcome-email')->first();

    expect($template)->not->toBeNull();
    expect($template->name)->toBe('Welcome Email');
    expect($template->category)->toBe(TemplateCategory::Transactional);
    expect($template->blocks)->toBeArray();
    expect($template->blocks)->not->toBeEmpty();

    $types = collect($template->blocks)->pluck('type')->toArray();
    expect($types)->toContain('preheader');
    expect($types)->toContain('header');
    expect($types)->toContain('hero');
    expect($types)->toContain('paragraph');
    expect($types)->toContain('footer');
});

it('creates a marketing template', function () {
    $this->artisan('mail-editor:make-template', [
        'name' => 'Promo Campaign',
        '--category' => 'marketing',
    ])->assertExitCode(0);

    $template = EmailTemplate::where('slug', 'promo-campaign')->first();
    expect($template->category)->toBe(TemplateCategory::Marketing);
});

it('fails when slug already exists', function () {
    EmailTemplate::create([
        'name' => 'Existing',
        'slug' => 'existing',
        'subject' => 'Test',
        'blocks' => [],
    ]);

    $this->artisan('mail-editor:make-template', ['name' => 'Existing'])
        ->assertExitCode(1);
});
