<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a large hero section with title, subtitle, CTA button, and optional background image.
 *
 * Uses VML (Vector Markup Language) for Outlook background image support,
 * since Outlook does not support CSS background-image on table cells.
 * Falls back to solid color on clients that don't support VML.
 *
 * @see https://backgrounds.cm — Bulletproof Email Backgrounds
 * @see https://www.campaignmonitor.com/blog/email-marketing/background-images-in-html-email/
 */
class HeroBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'hero';
    }

    public static function label(): string
    {
        return 'Hero';
    }

    public static function icon(): string
    {
        return 'heroicon-o-photo';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'bg_color' => '#185FA5',
            'bg_image' => null,
            'overlay_opacity' => 0.5,
            'title' => '',
            'subtitle' => '',
            'cta_text' => '',
            'cta_url' => '',
            'cta_bg_color' => '#ffffff',
            'cta_text_color' => '#185FA5',
            'align' => 'center',
        ];
    }
}
