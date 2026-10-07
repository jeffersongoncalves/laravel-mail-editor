<?php

namespace JeffersonGoncalves\MailEditor\Support;

class PlaintextGenerator
{
    public function generate(array $blocks): string
    {
        return collect($blocks)
            ->map(fn (array $block) => $this->blockToPlaintext($block))
            ->filter(fn (string $text) => $text !== '')
            ->join("\n\n");
    }

    protected function blockToPlaintext(array $block): string
    {
        $type = $block['type'] ?? '';
        $props = $block['props'] ?? [];

        return match ($type) {
            'heading' => strtoupper($props['text'] ?? '')."\n".str_repeat('=', 40),
            'paragraph' => $this->stripHtml($props['html'] ?? ''),
            'button' => ($props['text'] ?? 'Link').': '.($props['url'] ?? ''),
            'image' => '[Image: '.($props['alt'] ?? 'no description').']',
            'divider' => str_repeat('-', 40),
            'spacer' => '',
            'preheader' => '',
            'hero' => implode("\n", array_filter([
                $props['title'] ?? '',
                $props['subtitle'] ?? '',
                ! empty($props['cta_text']) ? ($props['cta_text'].': '.($props['cta_url'] ?? '')) : '',
            ])),
            'testimonial' => '"'.($props['quote'] ?? '').'" — '.($props['author'] ?? ''),
            'alert' => '['.strtoupper($props['type'] ?? 'INFO').'] '.($props['text'] ?? ''),
            'footer' => implode("\n", array_filter([
                $props['address'] ?? '',
                ! empty($props['unsubscribe_url']) ? ('Unsubscribe: '.$props['unsubscribe_url']) : '',
                $props['copyright'] ?? '',
            ])),
            'list' => $this->listToPlaintext($props),
            'product-card' => implode("\n", array_filter([
                $props['name'] ?? '',
                ! empty($props['old_price']) ? ('From: '.$props['old_price'].' -> '.$props['price']) : ($props['price'] ?? ''),
                $props['description'] ?? '',
                ! empty($props['cta_text']) ? ($props['cta_text'].': '.($props['cta_url'] ?? '')) : '',
            ])),
            'coupon' => implode("\n", array_filter([
                $props['discount_text'] ?? '',
                ! empty($props['code']) ? ('Code: '.$props['code']) : '',
                $props['expires_text'] ?? '',
            ])),
            'rating' => implode("\n", array_filter([
                str_repeat('*', (int) ($props['stars'] ?? 5)).'/5',
                $props['text'] ?? '',
                ! empty($props['author']) ? ('— '.$props['author']) : '',
            ])),
            'video-thumb' => '[Video: '.($props['alt'] ?? 'Watch video').']: '.($props['video_url'] ?? ''),
            'data-table' => $this->dataTableToPlaintext($props),
            'countdown' => ($props['label'] ?? 'Countdown').': '.($props['end_date'] ?? ''),
            'two-columns' => implode("\n\n", array_filter([
                $this->stripHtml($props['left_content'] ?? ''),
                $this->stripHtml($props['right_content'] ?? ''),
            ])),
            'three-columns' => implode("\n\n", array_filter([
                $this->stripHtml($props['col1_content'] ?? ''),
                $this->stripHtml($props['col2_content'] ?? ''),
                $this->stripHtml($props['col3_content'] ?? ''),
            ])),
            'header' => '',
            'logo-grid' => '[Logos]',
            default => '',
        };
    }

    protected function listToPlaintext(array $props): string
    {
        $items = $props['items'] ?? [];
        $type = $props['type'] ?? 'unordered';
        $bullet = $props['bullet_char'] ?? '*';

        return collect($items)
            ->map(function (string $item, int $index) use ($type, $bullet) {
                $prefix = $type === 'ordered' ? (($index + 1).'.') : $bullet;

                return "  {$prefix} {$item}";
            })
            ->join("\n");
    }

    protected function dataTableToPlaintext(array $props): string
    {
        $headers = $props['headers'] ?? [];
        $rows = $props['rows'] ?? [];

        if (empty($headers)) {
            return '';
        }

        $lines = [implode(' | ', $headers)];
        $lines[] = str_repeat('-', strlen($lines[0]));

        foreach ($rows as $row) {
            $lines[] = implode(' | ', (array) $row);
        }

        return implode("\n", $lines);
    }

    protected function stripHtml(string $html): string
    {
        $text = preg_replace('/<br\s*\/?>/i', "\n", $html);
        $text = preg_replace('/<\/p>/i', "\n", $text);
        $text = strip_tags($text);

        return html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');
    }
}
