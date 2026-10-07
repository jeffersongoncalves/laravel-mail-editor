<?php

namespace JeffersonGoncalves\MailEditor\Blocks;

/**
 * Renders an HTML data table with headers and optional striped rows.
 *
 * Uses native HTML <table> with thead/tbody for structured data display.
 * Supports configurable header colors, striping, and border styles.
 * Ideal for order summaries, pricing tables, and comparison charts.
 */
class DataTableBlock extends AbstractEmailBlock
{
    public static function type(): string
    {
        return 'data-table';
    }

    public static function label(): string
    {
        return 'Data Table';
    }

    public static function icon(): string
    {
        return 'heroicon-o-table-cells';
    }

    public static function category(): string
    {
        return 'content';
    }

    public static function defaultProps(): array
    {
        return [
            'headers' => [],
            'rows' => [],
            'striped' => true,
            'header_bg_color' => '#378ADD',
            'header_text_color' => '#ffffff',
            'stripe_color' => '#f8f9fa',
            'font_size' => 13,
            'border_color' => '#e8e8e8',
        ];
    }
}
