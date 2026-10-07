<?php

use JeffersonGoncalves\MailEditor\Support\LinkChecker;

beforeEach(function () {
    $this->checker = new LinkChecker;
});

it('extracts URLs from blocks', function () {
    $blocks = [
        ['type' => 'button', 'props' => ['text' => 'Click', 'url' => 'https://example.com']],
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.jpg', 'alt' => 'Img', 'link' => 'https://example.com/link']],
        ['type' => 'hero', 'props' => ['cta_url' => 'https://example.com/hero']],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => 'https://example.com/unsub']],
    ];

    $urls = $this->checker->extractUrls($blocks);

    expect($urls)->toHaveCount(5);
    expect(array_column($urls, 'url'))->toContain('https://example.com');
});

it('deduplicates URLs', function () {
    $blocks = [
        ['type' => 'button', 'props' => ['text' => 'Click 1', 'url' => 'https://example.com']],
        ['type' => 'button', 'props' => ['text' => 'Click 2', 'url' => 'https://example.com']],
    ];

    $urls = $this->checker->extractUrls($blocks);

    expect($urls)->toHaveCount(1);
});

it('extracts URLs from paragraph HTML', function () {
    $blocks = [
        ['type' => 'paragraph', 'props' => ['html' => '<p>Visit <a href="https://example.com">here</a></p>']],
    ];

    $urls = $this->checker->extractUrls($blocks);

    expect($urls)->toHaveCount(1);
    expect($urls[0]['url'])->toBe('https://example.com');
});

it('skips non-http URLs', function () {
    $blocks = [
        ['type' => 'button', 'props' => ['text' => 'Click', 'url' => '#']],
        ['type' => 'button', 'props' => ['text' => 'Mail', 'url' => 'mailto:test@test.com']],
    ];

    $urls = $this->checker->extractUrls($blocks);

    expect($urls)->toHaveCount(0);
});

it('extracts social link URLs from footer', function () {
    $blocks = [
        ['type' => 'footer', 'props' => [
            'social_links' => [
                ['platform' => 'twitter', 'url' => 'https://twitter.com/test'],
                ['platform' => 'github', 'url' => 'https://github.com/test'],
            ],
        ]],
    ];

    $urls = $this->checker->extractUrls($blocks);

    expect($urls)->toHaveCount(2);
});
