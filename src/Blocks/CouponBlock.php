<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a coupon/promo code block with a dashed border and monospace code.
 *
 * Uses Courier New monospace font for the code display to visually distinguish
 * it from regular text. Supports configurable border style (dashed/solid),
 * discount text, and expiration message.
 */
class CouponBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'coupon';
    }

    public static function label(): string
    {
        return 'Coupon';
    }

    public static function icon(): string
    {
        return 'heroicon-o-ticket';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'code' => '',
            'discount_text' => '',
            'expires_text' => '',
            'bg_color' => '#fff3cd',
            'border_color' => '#EF9F27',
            'border_style' => 'dashed',
            'text_color' => '#333333',
        ];
    }
}
