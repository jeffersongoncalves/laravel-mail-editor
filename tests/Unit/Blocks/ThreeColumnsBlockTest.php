<?php

use JeffersonGoncalves\MailEditor\Blocks\ThreeColumnsBlock;

it('renders three columns with inline-block layout', function () {
    $block = new ThreeColumnsBlock;
    $html = $block->render([
        'col1_content' => 'Column 1',
        'col2_content' => 'Column 2',
        'col3_content' => 'Column 3',
    ]);

    expect($html)
        ->toContain('Column 1')
        ->toContain('Column 2')
        ->toContain('Column 3')
        ->toContain('display:inline-block')
        ->toContain('33.33%')
        ->toContain('three-col-td');
});

it('provides media queries for mobile stacking', function () {
    $block = new ThreeColumnsBlock;

    expect($block->getMediaQueries())
        ->toContain('.three-col-td')
        ->toContain('display: block !important')
        ->toContain('width: 100% !important');
});

it('includes MSO conditional comments for Outlook', function () {
    $block = new ThreeColumnsBlock;
    $html = $block->render([]);

    expect($html)
        ->toContain('<!--[if mso]>')
        ->toContain('<![endif]-->');
});
