<?php

use JeffersonGoncalves\MailEditor\Support\HtmlExporter;

it('consolidates media queries from blocks into head', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([
        ['type' => 'two-columns', 'props' => ['left_content' => 'L', 'right_content' => 'R', 'stack_mobile' => true]],
        ['type' => 'three-columns', 'props' => ['col1_content' => 'A', 'col2_content' => 'B', 'col3_content' => 'C']],
    ], []);

    expect($html)
        ->toContain('.two-col-td')
        ->toContain('.three-col-td');
});

it('does not duplicate identical media queries', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([
        ['type' => 'two-columns', 'props' => ['left_content' => 'L1', 'right_content' => 'R1']],
        ['type' => 'two-columns', 'props' => ['left_content' => 'L2', 'right_content' => 'R2']],
    ], []);

    $count = substr_count($html, '.two-col-td');
    expect($count)->toBe(1);
});

it('includes dark mode styles in exported HTML', function () {
    $exporter = app(HtmlExporter::class);
    $html = $exporter->export([], [
        'dark_bg_color' => '#222222',
        'dark_text_color' => '#f0f0f0',
    ]);

    expect($html)
        ->toContain('color-scheme')
        ->toContain('prefers-color-scheme: dark');
});
