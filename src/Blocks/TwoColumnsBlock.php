<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a responsive two-column layout with configurable ratio and mobile stacking.
 *
 * Uses MSO conditional comments for Outlook table layout since Outlook ignores
 * display:inline-block. Columns stack vertically on mobile via media query.
 * Supports 50/50, 60/40, and 40/60 column ratios.
 *
 * @see https://www.emailonacid.com/blog/article/email-development/multi-column-email-layouts/
 */
class TwoColumnsBlock extends AbstractEmailBlock
{
    public function getMediaQueries(): string
    {
        return '@media only screen and (max-width: 600px) { .two-col-td { display: block !important; width: 100% !important; padding-left: 0 !important; padding-right: 0 !important; } }';
    }

    public static function type(): string
    {
        return 'two-columns';
    }

    public static function label(): string
    {
        return 'Two Columns';
    }

    public static function icon(): string
    {
        return 'heroicon-o-view-columns';
    }

    public static function category(): string
    {
        return 'structure';
    }

    public static function defaultProps(): array
    {
        return [
            'left_content' => '',
            'right_content' => '',
            'ratio' => '50-50',
            'gap' => 16,
            'bg_color_left' => null,
            'bg_color_right' => null,
            'stack_mobile' => true,
        ];
    }
}
