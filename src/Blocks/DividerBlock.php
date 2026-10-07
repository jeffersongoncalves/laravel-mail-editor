<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a horizontal divider line with configurable color, thickness, and style.
 *
 * Uses a table-based approach instead of <hr> for consistent cross-client rendering.
 * Supports solid, dashed, and dotted border styles.
 *
 * @see https://www.emailonacid.com/blog/article/email-development/horizontal-rules-in-html-email/
 */
class DividerBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'divider';
    }

    public static function label(): string
    {
        return 'Divider';
    }

    public static function icon(): string
    {
        return 'heroicon-o-minus';
    }

    public static function category(): string
    {
        return 'structure';
    }

    public static function defaultProps(): array
    {
        return [
            'color' => '#e8e8e8',
            'thickness' => 1,
            'width' => '100%',
            'style' => 'solid',
        ];
    }
}
