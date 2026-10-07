<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a responsive image with optional link, alt text, and border radius.
 *
 * Uses max-width:100% with a fixed width attribute for responsive scaling.
 * Alt text is required for accessibility and displayed when images are blocked.
 * Border radius has limited support in Outlook (falls back to square).
 *
 * @see https://www.caniemail.com/features/css-border-radius/
 */
class ImageBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'image';
    }

    public static function label(): string
    {
        return 'Image';
    }

    public static function icon(): string
    {
        return 'heroicon-o-photo';
    }

    public static function defaultProps(): array
    {
        return [
            'src' => '',
            'alt' => '',
            'link' => null,
            'width' => '100%',
            'align' => 'center',
            'border_radius' => 0,
        ];
    }
}
