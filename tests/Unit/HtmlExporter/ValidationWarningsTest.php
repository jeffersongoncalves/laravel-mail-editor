<?php

use JeffersonGoncalves\MailEditor\Support\HtmlExporter;

it('warns when no footer block is present', function () {
    $exporter = app(HtmlExporter::class);
    $warnings = $exporter->validate([
        ['type' => 'heading', 'props' => ['text' => 'Hello']],
    ]);

    expect($warnings)->toContain('No Footer block found. Unsubscribe is required by law (CAN-SPAM/LGPD).');
});

it('warns when no preheader block is present', function () {
    $exporter = app(HtmlExporter::class);
    $warnings = $exporter->validate([
        ['type' => 'heading', 'props' => ['text' => 'Hello']],
    ]);

    expect($warnings)->toContain('No Preheader block found. Recommended to improve open rates.');
});

it('warns about images without alt text', function () {
    $exporter = app(HtmlExporter::class);
    $warnings = $exporter->validate([
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.png', 'alt' => '']],
        ['type' => 'footer', 'props' => []],
        ['type' => 'preheader', 'props' => []],
    ]);

    expect($warnings)->toContain('Image without alt text. Required for accessibility.');
});

it('does not warn when footer and preheader are present', function () {
    $exporter = app(HtmlExporter::class);
    $warnings = $exporter->validate([
        ['type' => 'preheader', 'props' => ['text' => 'Preview']],
        ['type' => 'heading', 'props' => ['text' => 'Hello']],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => '#']],
    ]);

    expect($warnings)
        ->not->toContain('No Footer block found. Unsubscribe is required by law (CAN-SPAM/LGPD).')
        ->not->toContain('No Preheader block found. Recommended to improve open rates.');
});

it('does not warn about images with alt text', function () {
    $exporter = app(HtmlExporter::class);
    $warnings = $exporter->validate([
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.png', 'alt' => 'A nice image']],
        ['type' => 'preheader', 'props' => []],
        ['type' => 'footer', 'props' => []],
    ]);

    expect($warnings)->not->toContain('Image without alt text. Required for accessibility.');
});
