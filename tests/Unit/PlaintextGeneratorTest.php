<?php

use JeffersonGoncalves\MailEditor\Support\PlaintextGenerator;

beforeEach(function () {
    $this->generator = new PlaintextGenerator;
});

it('converts heading to uppercase with underline', function () {
    $result = $this->generator->generate([
        ['type' => 'heading', 'props' => ['text' => 'Welcome']],
    ]);

    expect($result)
        ->toContain('WELCOME')
        ->toContain('====');
});

it('strips HTML from paragraph', function () {
    $result = $this->generator->generate([
        ['type' => 'paragraph', 'props' => ['html' => '<p>Hello <strong>world</strong></p>']],
    ]);

    expect($result)
        ->toContain('Hello world')
        ->not->toContain('<p>')
        ->not->toContain('<strong>');
});

it('converts button to text with URL', function () {
    $result = $this->generator->generate([
        ['type' => 'button', 'props' => ['text' => 'Buy Now', 'url' => 'https://example.com']],
    ]);

    expect($result)->toContain('Buy Now: https://example.com');
});

it('converts image to alt text', function () {
    $result = $this->generator->generate([
        ['type' => 'image', 'props' => ['alt' => 'Product photo']],
    ]);

    expect($result)->toContain('[Image: Product photo]');
});

it('converts testimonial to quoted text', function () {
    $result = $this->generator->generate([
        ['type' => 'testimonial', 'props' => ['quote' => 'Great service', 'author' => 'John']],
    ]);

    expect($result)
        ->toContain('"Great service"')
        ->toContain('John');
});

it('converts footer with unsubscribe', function () {
    $result = $this->generator->generate([
        ['type' => 'footer', 'props' => ['address' => '123 Main St', 'unsubscribe_url' => 'https://example.com/unsub']],
    ]);

    expect($result)
        ->toContain('123 Main St')
        ->toContain('Unsubscribe: https://example.com/unsub');
});

it('converts product card', function () {
    $result = $this->generator->generate([
        ['type' => 'product-card', 'props' => ['name' => 'Widget', 'price' => '$29', 'old_price' => '$49', 'cta_text' => 'Buy', 'cta_url' => 'https://shop.com']],
    ]);

    expect($result)
        ->toContain('Widget')
        ->toContain('$49')
        ->toContain('$29')
        ->toContain('Buy: https://shop.com');
});

it('converts coupon code', function () {
    $result = $this->generator->generate([
        ['type' => 'coupon', 'props' => ['code' => 'SAVE20', 'discount_text' => '20% OFF']],
    ]);

    expect($result)
        ->toContain('Code: SAVE20')
        ->toContain('20% OFF');
});

it('handles divider as dashes', function () {
    $result = $this->generator->generate([
        ['type' => 'divider', 'props' => []],
    ]);

    expect($result)->toContain('----');
});

it('skips spacer and preheader', function () {
    $result = $this->generator->generate([
        ['type' => 'spacer', 'props' => ['height' => 24]],
        ['type' => 'preheader', 'props' => ['text' => 'Preview']],
    ]);

    expect($result)->toBe('');
});

it('converts countdown to label with date', function () {
    $result = $this->generator->generate([
        ['type' => 'countdown', 'props' => ['label' => 'Ends', 'end_date' => '2030-12-31']],
    ]);

    expect($result)->toContain('Ends: 2030-12-31');
});
