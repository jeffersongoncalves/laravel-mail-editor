<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders a list using table rows instead of HTML list elements.
 *
 * Email clients have inconsistent support for <ul>/<ol>/<li> and CSS list-style.
 * This block uses a table where each row has a bullet cell and a text cell,
 * ensuring consistent rendering across Gmail, Outlook, and Apple Mail.
 *
 * @see https://www.emailonacid.com/blog/article/email-development/html-lists-in-email/
 */
class ListBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'list';
    }

    public static function label(): string
    {
        return 'List';
    }

    public static function icon(): string
    {
        return 'heroicon-o-list-bullet';
    }

    public static function category(): string
    {
        return 'content';
    }

    public static function defaultProps(): array
    {
        return [
            'items' => [],
            'type' => 'unordered',
            'bullet_char' => "\u{2022}",
            'bullet_color' => '#378ADD',
            'indent' => 0,
            'color' => '#333333',
            'font_size' => 14,
        ];
    }
}
