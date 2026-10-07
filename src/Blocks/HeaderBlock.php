<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders the email header with logo and optional "view in browser" link.
 *
 * Provides a consistent top section with configurable background color,
 * logo image, and alignment. The logo uses max-width constraints for
 * responsive scaling on mobile clients.
 *
 * @see https://www.emailonacid.com/blog/article/email-development/email-header-best-practices/
 */
class HeaderBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'header';
    }

    public static function label(): string
    {
        return 'Header';
    }

    public static function icon(): string
    {
        return 'heroicon-o-bars-3';
    }

    public static function category(): string
    {
        return 'structure';
    }

    public static function defaultProps(): array
    {
        return [
            'logo_src' => '',
            'logo_alt' => '',
            'bg_color' => '#1A3A5C',
            'web_link' => '',
            'align' => 'center',
        ];
    }
}
