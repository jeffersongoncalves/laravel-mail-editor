<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a bulletproof CTA button compatible with all major email clients.
 *
 * Uses VML for Outlook (2013-2021) and a standard <a> tag with padding for
 * others. The VML wrapping ensures Outlook renders rounded corners correctly.
 * Supports configurable colors, border radius, alignment, and full-width mode.
 *
 * @see https://buttons.cm — Bulletproof Email Buttons reference
 * @see https://www.campaignmonitor.com/blog/email-marketing/using-vml-ms-only-css-outlook/
 */
class ButtonBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'button';
    }

    public static function label(): string
    {
        return 'Button';
    }

    public static function icon(): string
    {
        return 'heroicon-o-cursor-arrow-rays';
    }

    public static function defaultProps(): array
    {
        return [
            'text' => '',
            'url' => '',
            'bg_color' => '#378ADD',
            'text_color' => '#ffffff',
            'border_radius' => 4,
            'align' => 'center',
            'width' => 'auto',
            'font_size' => 14,
            'padding' => '12px 28px',
        ];
    }
}
