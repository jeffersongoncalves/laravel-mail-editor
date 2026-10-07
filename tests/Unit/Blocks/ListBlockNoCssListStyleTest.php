<?php

use JeffersonGoncalves\MailEditor\Blocks\ListBlock;

it('renders list items using table rows, not CSS list-style', function () {
    $block = new ListBlock;
    $html = $block->render([
        'items' => [
            ['text' => 'First item'],
            ['text' => 'Second item'],
        ],
    ]);

    expect($html)
        ->toContain('First item')
        ->toContain('Second item')
        ->toContain('<tr>')
        ->toContain('<td')
        ->not->toContain('list-style')
        ->not->toContain('<ul')
        ->not->toContain('<ol')
        ->not->toContain('<li');
});

it('renders bullet character for unordered lists', function () {
    $block = new ListBlock;
    $html = $block->render([
        'items' => [['text' => 'Item']],
        'type' => 'unordered',
        'bullet_char' => "\u{2022}",
        'bullet_color' => '#ff0000',
    ]);

    expect($html)
        ->toContain("\u{2022}")
        ->toContain('#ff0000');
});

it('renders numbers for ordered lists', function () {
    $block = new ListBlock;
    $html = $block->render([
        'items' => [['text' => 'First'], ['text' => 'Second']],
        'type' => 'ordered',
    ]);

    expect($html)
        ->toContain('1.')
        ->toContain('2.');
});

it('renders links when provided', function () {
    $block = new ListBlock;
    $html = $block->render([
        'items' => [['text' => 'Click here', 'link' => 'https://example.com']],
    ]);

    expect($html)
        ->toContain('href="https://example.com"')
        ->toContain('Click here');
});
