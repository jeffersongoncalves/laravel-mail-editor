<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Support\SpamScoreAnalyzer;

beforeEach(function () {
    $this->analyzer = new SpamScoreAnalyzer;
});

it('returns a score between 0 and 100', function () {
    $template = new EmailTemplate;
    $template->subject = 'Welcome to our service';
    $template->blocks = [
        ['type' => 'paragraph', 'props' => ['html' => 'Some text content here for a good ratio.']],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => 'https://example.com/unsubscribe', 'address' => '123 St']],
        ['type' => 'preheader', 'props' => ['text' => 'This is a preview text for the email inbox display.']],
    ];

    $result = $this->analyzer->analyze($template);

    expect($result['score'])->toBeGreaterThanOrEqual(0);
    expect($result['score'])->toBeLessThanOrEqual(100);
    expect($result['checks'])->toBeArray();
});

it('penalizes image-only emails', function () {
    $template = new EmailTemplate;
    $template->subject = 'Check this out';
    $template->blocks = [
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.jpg', 'alt' => 'Image']],
    ];

    $result = $this->analyzer->analyze($template);

    $textRatio = collect($result['checks'])->firstWhere('label', 'Text/Image Ratio');
    expect($textRatio['status'])->toBe('error');
    expect($textRatio['points'])->toBeLessThan(0);
});

it('penalizes missing unsubscribe', function () {
    $template = new EmailTemplate;
    $template->subject = 'Hello World subject line';
    $template->blocks = [
        ['type' => 'paragraph', 'props' => ['html' => 'Content']],
    ];

    $result = $this->analyzer->analyze($template);

    $unsub = collect($result['checks'])->firstWhere('label', 'Unsubscribe');
    expect($unsub['status'])->toBe('error');
});

it('detects spam words', function () {
    $template = new EmailTemplate;
    $template->subject = 'ACT NOW! FREE prize winner!';
    $template->blocks = [
        ['type' => 'paragraph', 'props' => ['html' => 'Click here to buy now! Limited time offer! Free free free!']],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => 'https://example.com/unsub']],
    ];

    $result = $this->analyzer->analyze($template);

    $spam = collect($result['checks'])->firstWhere('label', 'Spam Words');
    expect($spam['status'])->not->toBe('ok');
});

it('detects url shorteners', function () {
    $template = new EmailTemplate;
    $template->subject = 'Check this valid subject line';
    $template->blocks = [
        ['type' => 'button', 'props' => ['url' => 'https://bit.ly/abc123', 'text' => 'Click']],
    ];

    $result = $this->analyzer->analyze($template);

    $urls = collect($result['checks'])->firstWhere('label', 'URL Patterns');
    expect($urls['status'])->toBe('warning');
});

it('gives high score for well-formed email', function () {
    $template = new EmailTemplate;
    $template->subject = 'Your monthly newsletter update';
    $template->blocks = [
        ['type' => 'preheader', 'props' => ['text' => 'Here is your monthly newsletter with updates and news.']],
        ['type' => 'heading', 'props' => ['text' => 'Newsletter', 'level' => 'h1']],
        ['type' => 'paragraph', 'props' => ['html' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor.']],
        ['type' => 'button', 'props' => ['text' => 'Read More', 'url' => 'https://example.com/article']],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => 'https://example.com/unsubscribe', 'address' => '123 Main St']],
    ];

    $result = $this->analyzer->analyze($template);

    expect($result['score'])->toBeGreaterThanOrEqual(80);
});
