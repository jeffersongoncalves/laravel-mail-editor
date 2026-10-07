<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders rich text content with configurable typography settings.
 *
 * Supports HTML content with inline formatting (bold, italic, links).
 * Line height, font size, and color are applied via inline styles
 * for consistent rendering across email clients.
 *
 * @see https://www.caniemail.com/features/css-line-height/
 */
class ParagraphBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'paragraph';
    }

    public static function label(): string
    {
        return 'Paragraph';
    }

    public static function icon(): string
    {
        return 'heroicon-o-bars-3-bottom-left';
    }

    public static function defaultProps(): array
    {
        return [
            'html' => '',
            'color' => '#555555',
            'font_size' => 14,
            'line_height' => 1.7,
            'align' => 'left',
        ];
    }
}
