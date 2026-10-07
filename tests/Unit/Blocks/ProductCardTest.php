<?php

use JeffersonGoncalves\MailEditor\Blocks\ProductCardBlock;

it('renders product card with name and price', function () {
    $block = new ProductCardBlock;
    $html = $block->render([
        'name' => 'Test Product',
        'price' => '$99.99',
        'cta_text' => 'Buy Now',
        'cta_url' => 'https://shop.example.com',
    ]);

    expect($html)
        ->toContain('Test Product')
        ->toContain('$99.99')
        ->toContain('Buy Now')
        ->toContain('href="https://shop.example.com"');
});

it('renders old price with strikethrough', function () {
    $block = new ProductCardBlock;
    $html = $block->render([
        'name' => 'Sale Item',
        'price' => '$49.99',
        'old_price' => '$79.99',
    ]);

    expect($html)
        ->toContain('$49.99')
        ->toContain('$79.99')
        ->toContain('text-decoration:line-through');
});

it('renders badge when provided', function () {
    $block = new ProductCardBlock;
    $html = $block->render([
        'name' => 'Product',
        'price' => '$10',
        'image_src' => 'https://example.com/product.jpg',
        'badge_text' => 'SALE',
        'badge_bg_color' => '#e53e3e',
    ]);

    expect($html)
        ->toContain('SALE')
        ->toContain('#e53e3e');
});

it('includes VML for Outlook CTA button', function () {
    $block = new ProductCardBlock;
    $html = $block->render([
        'name' => 'P',
        'price' => '$1',
        'cta_text' => 'Shop',
        'cta_url' => 'https://example.com',
    ]);

    expect($html)
        ->toContain('v:roundrect')
        ->toContain('<!--[if mso]>');
});
