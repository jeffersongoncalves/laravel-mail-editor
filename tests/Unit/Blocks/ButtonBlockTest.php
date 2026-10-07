<?php

use JeffersonGoncalves\MailEditor\Blocks\ButtonBlock;

it('has correct type and label', function () {
    expect(ButtonBlock::type())->toBe('button');
    expect(ButtonBlock::label())->toBe('Button');
    expect(ButtonBlock::category())->toBe('content');
});

it('renders bulletproof VML for Outlook', function () {
    $block = new ButtonBlock;
    $html = $block->render([
        'text' => 'Click Me',
        'url' => 'https://example.com',
        'bg_color' => '#378ADD',
        'text_color' => '#ffffff',
        'border_radius' => 4,
        'font_size' => 14,
        'padding' => '12px 28px',
        'align' => 'center',
        'width' => 'auto',
    ]);

    expect($html)
        ->toContain('<!--[if mso]>')
        ->toContain('v:roundrect')
        ->toContain('href="https://example.com"')
        ->toContain('Click Me')
        ->toContain('#378ADD')
        ->toContain('#ffffff')
        ->toContain('<!--[if !mso]><!-->')
        ->toContain('<!--<![endif]-->');
});

it('renders full width button', function () {
    $block = new ButtonBlock;
    $html = $block->render([
        'text' => 'Full Width',
        'url' => 'https://example.com',
        'width' => 'full',
    ]);

    expect($html)
        ->toContain('width:100%')
        ->toContain('display:block');
});

it('uses default props when not provided', function () {
    $defaults = ButtonBlock::defaultProps();

    expect($defaults)
        ->toHaveKey('bg_color', '#378ADD')
        ->toHaveKey('text_color', '#ffffff')
        ->toHaveKey('border_radius', 4)
        ->toHaveKey('font_size', 14);
});
