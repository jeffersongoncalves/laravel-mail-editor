<?php

use JeffersonGoncalves\MailEditor\Models\EmailBrandKit;

beforeEach(function () {
    // Create the brand kits table for testing
    $this->artisan('migrate', ['--database' => 'testing']);
});

it('creates a brand kit', function () {
    $kit = EmailBrandKit::create([
        'name' => 'Acme Brand',
        'slug' => 'acme-brand',
        'colors' => [
            'primary_color' => '#FF0000',
            'button_bg' => '#FF0000',
            'button_text' => '#FFFFFF',
            'text_color' => '#333333',
        ],
        'typography' => [
            'font_family' => 'Arial, sans-serif',
            'font_size_base' => 16,
        ],
    ]);

    expect($kit->name)->toBe('Acme Brand');
    expect($kit->colors['primary_color'])->toBe('#FF0000');
});

it('converts to theme array', function () {
    $kit = EmailBrandKit::create([
        'name' => 'Test',
        'slug' => 'test',
        'colors' => ['primary_color' => '#0000FF', 'button_bg' => '#0000FF'],
        'typography' => ['font_family' => 'Georgia, serif', 'font_size_base' => 14],
    ]);

    $theme = $kit->toThemeArray();

    expect($theme['primary_color'])->toBe('#0000FF');
    expect($theme['font_family'])->toBe('Georgia, serif');
});

it('supports default brand kit', function () {
    EmailBrandKit::create([
        'name' => 'Default',
        'slug' => 'default',
        'is_default' => true,
        'colors' => ['primary_color' => '#000'],
        'typography' => ['font_family' => 'Arial'],
    ]);

    $default = EmailBrandKit::getDefault();

    expect($default)->not->toBeNull();
    expect($default->name)->toBe('Default');
});

it('ensures only one default', function () {
    $kit1 = EmailBrandKit::create([
        'name' => 'Kit 1',
        'slug' => 'kit-1',
        'is_default' => true,
        'colors' => ['primary_color' => '#000'],
        'typography' => [],
    ]);

    $kit2 = EmailBrandKit::create([
        'name' => 'Kit 2',
        'slug' => 'kit-2',
        'is_default' => false,
        'colors' => ['primary_color' => '#FFF'],
        'typography' => [],
    ]);

    $kit2->setAsDefault();

    expect($kit1->fresh()->is_default)->toBeFalse();
    expect($kit2->fresh()->is_default)->toBeTrue();
});
