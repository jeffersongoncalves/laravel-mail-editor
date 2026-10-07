<?php

use JeffersonGoncalves\MailEditor\Support\HtmlExporter;

it('exports a complete HTML email document', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([], []);

    expect($html)
        ->toContain('<!DOCTYPE')
        ->toContain('xmlns:v="urn:schemas-microsoft-com:vml"')
        ->toContain('</html>');
});

it('renders blocks in order', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([
        ['type' => 'heading', 'props' => ['text' => 'First Heading', 'level' => 'h1']],
        ['type' => 'paragraph', 'props' => ['html' => 'Some paragraph text']],
        ['type' => 'button', 'props' => ['text' => 'Click Me', 'url' => 'https://example.com']],
    ], []);

    $headingPos = strpos($html, 'First Heading');
    $paragraphPos = strpos($html, 'Some paragraph text');
    $buttonPos = strpos($html, 'Click Me');

    expect($headingPos)->toBeLessThan($paragraphPos);
    expect($paragraphPos)->toBeLessThan($buttonPos);
});

it('applies default settings', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([], [
        'bg_color' => '#f0f0f0',
        'font_family' => 'Georgia, serif',
    ]);

    expect($html)
        ->toContain('#f0f0f0')
        ->toContain('Georgia, serif');
});

it('ignores unknown block types', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([
        ['type' => 'nonexistent-block', 'props' => ['text' => 'Should be ignored']],
        ['type' => 'heading', 'props' => ['text' => 'Visible Heading']],
    ], []);

    expect($html)
        ->toContain('Visible Heading')
        ->not->toContain('Should be ignored');
});
