<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a product card with image, badge, pricing, and CTA button.
 *
 * Supports old/new price display with strikethrough, image badges (SALE, NEW),
 * and a VML-based CTA button for Outlook compatibility. The card layout uses
 * a bordered table container for consistent rendering.
 *
 * @see https://buttons.cm — Bulletproof Email Buttons for the CTA
 */
class ProductCardBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'product-card';
    }

    public static function label(): string
    {
        return 'Product Card';
    }

    public static function icon(): string
    {
        return 'heroicon-o-shopping-bag';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'image_src' => '',
            'image_alt' => '',
            'name' => '',
            'price' => '',
            'old_price' => '',
            'description' => '',
            'cta_text' => 'Buy Now',
            'cta_url' => '',
            'cta_bg_color' => '#378ADD',
            'badge_text' => '',
            'badge_bg_color' => '#e53e3e',
        ];
    }
}
