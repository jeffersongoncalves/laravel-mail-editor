<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a star rating display using Unicode characters.
 *
 * Uses Unicode filled star (U+2605) and empty star (U+2606) for maximum
 * email client compatibility without requiring images. Supports 1-5 stars
 * with configurable color and optional review text with author attribution.
 */
class RatingBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'rating';
    }

    public static function label(): string
    {
        return 'Rating';
    }

    public static function icon(): string
    {
        return 'heroicon-o-star';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'stars' => 5,
            'text' => '',
            'author' => '',
            'star_color' => '#EF9F27',
        ];
    }
}
