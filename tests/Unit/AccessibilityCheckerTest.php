<?php

use JeffersonGoncalves\MailEditor\Support\AccessibilityChecker;

beforeEach(function () {
    $this->checker = new AccessibilityChecker;
});

it('flags images without alt text', function () {
    $blocks = [
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.jpg', 'alt' => '']],
    ];

    $results = $this->checker->check($blocks);
    $imageCheck = collect($results)->firstWhere('label', 'Image Alt Text');

    expect($imageCheck['status'])->toBe('error');
    expect($imageCheck['wcag'])->toBe('1.1.1');
});

it('passes when all images have alt text', function () {
    $blocks = [
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.jpg', 'alt' => 'Description']],
    ];

    $results = $this->checker->check($blocks);
    $imageCheck = collect($results)->firstWhere('label', 'Image Alt Text');

    expect($imageCheck['status'])->toBe('ok');
});

it('checks the alt text of header logos and product card images', function () {
    $blocks = [
        ['type' => 'header', 'props' => ['logo_src' => 'https://example.com/logo.png', 'logo_alt' => '']],
        ['type' => 'product-card', 'props' => ['image_src' => 'https://example.com/p.jpg', 'image_alt' => '']],
        ['type' => 'product-card', 'props' => ['image_src' => '', 'image_alt' => '']],
        ['type' => 'paragraph', 'props' => ['text' => 'No image here']],
    ];

    $imageCheck = collect($this->checker->check($blocks))->firstWhere('label', 'Image Alt Text');

    expect($imageCheck['status'])->toBe('error')
        ->and($imageCheck['message'])->toStartWith('2 image(s)');
});

it('detects low color contrast', function () {
    $blocks = [
        ['type' => 'paragraph', 'props' => ['color' => '#cccccc', 'bg_color' => '#ffffff']],
    ];

    $results = $this->checker->check($blocks);
    $contrast = collect($results)->firstWhere('label', 'Color Contrast');

    expect($contrast['status'])->toBe('warning');
});

it('passes good color contrast', function () {
    $blocks = [
        ['type' => 'paragraph', 'props' => ['color' => '#000000', 'bg_color' => '#ffffff']],
    ];

    $results = $this->checker->check($blocks);
    $contrast = collect($results)->firstWhere('label', 'Color Contrast');

    expect($contrast['status'])->toBe('ok');
});

it('flags generic link text', function () {
    $blocks = [
        ['type' => 'button', 'props' => ['text' => 'Click Here', 'url' => 'https://example.com']],
    ];

    $results = $this->checker->check($blocks);
    $linkText = collect($results)->firstWhere('label', 'Link Text');

    expect($linkText['status'])->toBe('warning');
    expect($linkText['wcag'])->toBe('2.4.4');
});

it('flags skipped heading levels', function () {
    $blocks = [
        ['type' => 'heading', 'props' => ['text' => 'Title', 'level' => 'h1']],
        ['type' => 'heading', 'props' => ['text' => 'Sub', 'level' => 'h3']], // skipped h2
    ];

    $results = $this->checker->check($blocks);
    $headings = collect($results)->firstWhere('label', 'Heading Hierarchy');

    expect($headings['status'])->toBe('warning');
});

it('flags buttons without labels', function () {
    $blocks = [
        ['type' => 'button', 'props' => ['text' => '', 'url' => 'https://example.com']],
    ];

    $results = $this->checker->check($blocks);
    $buttons = collect($results)->firstWhere('label', 'Button Labels');

    expect($buttons['status'])->toBe('error');
});

it('flags small font sizes', function () {
    $blocks = [
        ['type' => 'paragraph', 'props' => ['html' => 'Small text', 'font_size' => 10]],
    ];

    $results = $this->checker->check($blocks);
    $fonts = collect($results)->firstWhere('label', 'Font Sizes');

    expect($fonts['status'])->toBe('warning');
});

it('returns wcag references for each check', function () {
    $results = $this->checker->check([]);

    foreach ($results as $result) {
        expect($result)->toHaveKey('wcag');
        expect($result['wcag'])->toMatch('/\d+\.\d+\.\d+/');
    }
});
