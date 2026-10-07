<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Support\QualityChecker;

beforeEach(function () {
    $this->checker = new QualityChecker;
});

it('detects missing preheader', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'heading', 'props' => ['text' => 'Hello']],
    ];
    $template->subject = 'A good subject line here';

    $results = $this->checker->check($template);
    $preheader = collect($results)->firstWhere('label', 'Preheader');

    expect($preheader['status'])->toBe('warning');
});

it('detects missing unsubscribe', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'heading', 'props' => ['text' => 'Hello']],
    ];
    $template->subject = 'A good subject line here';

    $results = $this->checker->check($template);
    $unsub = collect($results)->firstWhere('label', 'Unsubscribe');

    expect($unsub['status'])->toBe('error');
});

it('passes with complete template', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'preheader', 'props' => ['text' => str_repeat('A', 50)]],
        ['type' => 'heading', 'props' => ['text' => 'Welcome', 'font_size' => 24]],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => 'https://example.com/unsub']],
    ];
    $template->subject = 'Welcome to our newsletter';

    $results = $this->checker->check($template);
    $statuses = collect($results)->pluck('status');

    expect($statuses)->not->toContain('error');
});

it('detects images without alt text', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'preheader', 'props' => ['text' => str_repeat('A', 50)]],
        ['type' => 'image', 'props' => ['src' => 'https://example.com/img.jpg', 'alt' => '']],
        ['type' => 'footer', 'props' => ['unsubscribe_url' => 'https://example.com/unsub']],
    ];
    $template->subject = 'A good subject line here';

    $results = $this->checker->check($template);
    $alt = collect($results)->firstWhere('label', 'Image Alt Text');

    expect($alt['status'])->toBe('warning');
});

it('detects short subject', function () {
    $template = new EmailTemplate;
    $template->blocks = [];
    $template->subject = 'Hi';

    $results = $this->checker->check($template);
    $subject = collect($results)->firstWhere('label', 'Subject Line');

    expect($subject['status'])->toBe('warning');
});

it('detects placeholder button URLs', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'button', 'props' => ['text' => 'Click', 'url' => '#']],
    ];
    $template->subject = 'A good subject line here';

    $results = $this->checker->check($template);
    $buttons = collect($results)->firstWhere('label', 'Button URLs');

    expect($buttons['status'])->toBe('error');
});

it('detects localhost images', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'image', 'props' => ['src' => 'http://localhost/img.jpg', 'alt' => 'Test']],
    ];
    $template->subject = 'A good subject line here';

    $results = $this->checker->check($template);
    $hosted = collect($results)->firstWhere('label', 'Images Hosted');

    expect($hosted['status'])->toBe('error');
});

it('detects small font sizes', function () {
    $template = new EmailTemplate;
    $template->blocks = [
        ['type' => 'paragraph', 'props' => ['html' => 'Text', 'font_size' => 10]],
    ];
    $template->subject = 'A good subject line here';

    $results = $this->checker->check($template);
    $fontSize = collect($results)->firstWhere('label', 'Font Size');

    expect($fontSize['status'])->toBe('warning');
});

it('returns 9 checks total', function () {
    $template = new EmailTemplate;
    $template->blocks = [];
    $template->subject = 'Test';

    $results = $this->checker->check($template);

    expect($results)->toHaveCount(9);
});
