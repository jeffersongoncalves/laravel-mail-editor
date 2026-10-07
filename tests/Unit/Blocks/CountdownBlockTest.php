<?php

use JeffersonGoncalves\MailEditor\Blocks\CountdownBlock;

it('renders countdown with image tag when end_date is set', function () {
    config(['mail-editor.app_url' => 'https://app.example.com']);

    $block = new CountdownBlock;
    $html = $block->render([
        'end_date' => '2030-12-31T23:59:59',
        'timezone' => 'America/Sao_Paulo',
        'label' => 'Sale ends in',
        'style' => 'default',
        'width' => 500,
        'height' => 80,
        'expired_text' => 'Sale over',
    ]);

    expect($html)
        ->toContain('img')
        ->toContain('https://app.example.com/mail-editor/countdown')
        ->toContain('alt="Sale ends in"');
});

it('renders fallback when no end_date is set', function () {
    $block = new CountdownBlock;
    $html = $block->render([
        'end_date' => null,
        'label' => 'Offer ends',
        'expired_text' => 'Offer expired',
    ]);

    expect($html)
        ->toContain('Offer ends')
        ->toContain('Offer expired')
        ->not->toContain('<img');
});

it('has correct type and category', function () {
    expect(CountdownBlock::type())->toBe('countdown');
    expect(CountdownBlock::category())->toBe('marketing');
    expect(CountdownBlock::label())->toBe('Countdown');
});
