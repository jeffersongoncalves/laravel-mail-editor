<?php

use JeffersonGoncalves\MailEditor\Support\ThemeApplier;

beforeEach(function () {
    $this->applier = new ThemeApplier;
});

it('applies theme colors to button blocks', function () {
    $blocks = [
        ['id' => 'b_1', 'type' => 'button', 'props' => ['text' => 'Click', 'bg_color' => '#000', 'text_color' => '#fff']],
    ];

    $theme = ['button_bg' => '#FF0000', 'button_text' => '#FFFFFF'];
    $result = $this->applier->apply($blocks, $theme);

    expect($result[0]['props']['bg_color'])->toBe('#FF0000');
    expect($result[0]['props']['text_color'])->toBe('#FFFFFF');
});

it('applies font family to eligible blocks', function () {
    $blocks = [
        ['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Hi']],
        ['id' => 'b_2', 'type' => 'paragraph', 'props' => ['html' => 'Text']],
        ['id' => 'b_3', 'type' => 'footer', 'props' => ['address' => 'Addr']],
    ];

    $theme = ['font_family' => 'Georgia, serif'];
    $result = $this->applier->apply($blocks, $theme);

    expect($result[0]['props']['font_family'])->toBe('Georgia, serif');
    expect($result[1]['props']['font_family'])->toBe('Georgia, serif');
    // Footer should NOT get font_family
    expect($result[2]['props'])->not->toHaveKey('font_family');
});

it('resolves @ references in settings', function () {
    $settings = [
        'primary_color' => '#378ADD',
        'button_bg' => '@primary_color',
        'text_color' => '#333',
    ];

    $result = $this->applier->resolveReferences($settings);

    expect($result['button_bg'])->toBe('#378ADD');
    expect($result['text_color'])->toBe('#333');
});

it('does not modify blocks without theme mapping', function () {
    $blocks = [
        ['id' => 'b_1', 'type' => 'spacer', 'props' => ['height' => 24]],
    ];

    $theme = ['button_bg' => '#FF0000'];
    $result = $this->applier->apply($blocks, $theme);

    expect($result[0]['props'])->toBe(['height' => 24]);
});
