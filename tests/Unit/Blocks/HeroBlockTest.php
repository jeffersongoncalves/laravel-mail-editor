<?php

use JeffersonGoncalves\MailEditor\Blocks\HeroBlock;

it('has correct type and label', function () {
    expect(HeroBlock::type())->toBe('hero');
    expect(HeroBlock::label())->toBe('Hero');
    expect(HeroBlock::category())->toBe('marketing');
});

it('renders basic hero without background image', function () {
    $block = new HeroBlock;
    $html = $block->render([
        'title' => 'Welcome!',
        'subtitle' => 'This is a subtitle',
        'bg_color' => '#185FA5',
        'align' => 'center',
    ]);

    expect($html)
        ->toContain('Welcome!')
        ->toContain('This is a subtitle')
        ->toContain('#185FA5');
});

it('renders VML background image for Outlook', function () {
    $block = new HeroBlock;
    $html = $block->render([
        'title' => 'Hero Title',
        'bg_color' => '#185FA5',
        'bg_image' => 'https://example.com/hero.jpg',
        'align' => 'center',
    ]);

    expect($html)
        ->toContain('<!--[if gte mso 9]>')
        ->toContain('v:rect')
        ->toContain('v:fill')
        ->toContain('https://example.com/hero.jpg')
        ->toContain('Hero Title');
});

it('renders CTA button when provided', function () {
    $block = new HeroBlock;
    $html = $block->render([
        'title' => 'Title',
        'cta_text' => 'Get Started',
        'cta_url' => 'https://example.com/signup',
        'cta_bg_color' => '#ffffff',
        'cta_text_color' => '#185FA5',
        'align' => 'center',
    ]);

    expect($html)
        ->toContain('Get Started')
        ->toContain('https://example.com/signup');
});

it('uses default props when not provided', function () {
    $defaults = HeroBlock::defaultProps();

    expect($defaults)
        ->toHaveKey('bg_color', '#185FA5')
        ->toHaveKey('align', 'center');
});
