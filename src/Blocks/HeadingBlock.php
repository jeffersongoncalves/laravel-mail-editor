<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a heading element (H1-H4) with configurable font, color, and alignment.
 *
 * Uses inline styles exclusively for email client compatibility. Font family
 * falls back to web-safe fonts since custom web fonts have limited support.
 *
 * @see https://www.caniemail.com/features/css-font-family/
 */
class HeadingBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'heading';
    }

    public static function label(): string
    {
        return 'Heading';
    }

    public static function icon(): string
    {
        return 'heroicon-o-h1';
    }

    public static function defaultProps(): array
    {
        return [
            'text' => '',
            'level' => 'h2',
            'color' => '#1a1a1a',
            'font_size' => null,
            'align' => 'left',
            'font_family' => 'Arial, sans-serif',
        ];
    }
}
