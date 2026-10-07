<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders an alert/notification box with type-based color presets.
 *
 * Supports info, warning, error, and success types with automatic
 * color selection. Custom colors override the type defaults.
 * Uses a left border accent for visual distinction.
 */
class AlertBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'alert';
    }

    public static function label(): string
    {
        return 'Alert';
    }

    public static function icon(): string
    {
        return 'heroicon-o-exclamation-triangle';
    }

    public static function defaultProps(): array
    {
        return [
            'type' => 'info',
            'text' => '',
            'bg_color' => null,
            'text_color' => null,
            'border_color' => null,
        ];
    }
}
