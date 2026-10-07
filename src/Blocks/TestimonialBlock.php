<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a customer testimonial with quote, author, role, and optional avatar.
 *
 * Uses a left border accent and italic quote styling for visual distinction.
 * The avatar image is circular (border-radius:50%) with fallback to square
 * in Outlook. Supports configurable accent color for brand consistency.
 */
class TestimonialBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'testimonial';
    }

    public static function label(): string
    {
        return 'Testimonial';
    }

    public static function icon(): string
    {
        return 'heroicon-o-chat-bubble-bottom-center-text';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'quote' => '',
            'author' => '',
            'role' => '',
            'avatar_src' => null,
            'accent_color' => '#378ADD',
        ];
    }
}
