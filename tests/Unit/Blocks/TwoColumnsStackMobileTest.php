<?php

use JeffersonGoncalves\MailEditor\Blocks\TwoColumnsBlock;

it('uses two-col-td class for mobile stacking', function () {
    $block = new TwoColumnsBlock;
    $html = $block->render([
        'left_content' => 'Left',
        'right_content' => 'Right',
        'stack_mobile' => true,
    ]);

    expect($html)
        ->toContain('two-col-td')
        ->not->toContain('<style');
});

it('does not add mobile class when stacking is disabled', function () {
    $block = new TwoColumnsBlock;
    $html = $block->render([
        'left_content' => 'Left',
        'right_content' => 'Right',
        'stack_mobile' => false,
    ]);

    expect($html)->not->toContain('two-col-td');
});

it('provides media queries via getMediaQueries', function () {
    $block = new TwoColumnsBlock;

    expect($block->getMediaQueries())
        ->toContain('.two-col-td')
        ->toContain('display: block !important')
        ->toContain('width: 100% !important');
});
