<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders the email footer with address, unsubscribe link, and copyright.
 *
 * The unsubscribe link is legally required in marketing emails (CAN-SPAM, LGPD, GDPR).
 * Footer includes optional social links and "view in browser" URL.
 * Uses small font size and muted colors per email design conventions.
 *
 * @see https://www.ftc.gov/business-guidance/resources/can-spam-act-compliance-guide-business
 */
class FooterBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'footer';
    }

    public static function label(): string
    {
        return 'Footer';
    }

    public static function icon(): string
    {
        return 'heroicon-o-bars-3-bottom-right';
    }

    public static function category(): string
    {
        return 'structure';
    }

    public static function defaultProps(): array
    {
        return [
            'address' => '',
            'unsubscribe_url' => '',
            'unsubscribe_text' => 'Unsubscribe',
            'web_version_url' => null,
            'copyright' => null,
            'social_links' => [],
            'bg_color' => '#f8f9fa',
            'text_color' => '#999999',
            'font_size' => 11,
        ];
    }
}
