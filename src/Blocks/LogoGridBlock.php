<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a grid of logos with configurable columns and optional grayscale filter.
 *
 * Uses a table-based grid layout for cross-client compatibility. Each logo
 * can optionally link to a URL. The grayscale CSS filter works in modern
 * clients but is ignored in Outlook (logos appear in full color).
 *
 * @see https://www.caniemail.com/features/css-filter/
 */
class LogoGridBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'logo-grid';
    }

    public static function label(): string
    {
        return 'Logo Grid';
    }

    public static function icon(): string
    {
        return 'heroicon-o-squares-2x2';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'logos' => [],
            'cols' => 3,
            'grayscale' => true,
            'cell_padding' => 16,
        ];
    }
}
