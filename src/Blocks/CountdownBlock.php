<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a countdown timer as a server-generated image.
 *
 * Email clients do not support JavaScript or CSS animations, so the only
 * cross-client solution is a server-side generated image. This block renders
 * an <img> tag pointing to a route that generates a countdown image via GD.
 *
 * The image URL is absolute, using the configured app_url. When the countdown
 * expires, the route returns a static "expired" image instead.
 *
 * @see https://www.litmus.com/blog/countdown-timers-in-email
 * @see https://www.emailonacid.com/blog/article/email-development/countdown-timers-in-email/
 */
class CountdownBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'countdown';
    }

    public static function label(): string
    {
        return 'Countdown';
    }

    public static function icon(): string
    {
        return 'heroicon-o-clock';
    }

    public static function category(): string
    {
        return 'marketing';
    }

    public static function defaultProps(): array
    {
        return [
            'end_date' => null,
            'timezone' => 'America/Sao_Paulo',
            'label' => 'Offer ends in',
            'style' => 'default',
            'width' => 500,
            'height' => 80,
            'expired_text' => 'Offer expired',
        ];
    }
}
