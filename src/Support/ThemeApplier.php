<?php

namespace JeffersonGoncalves\MailEditor\Support;

class ThemeApplier
{
    /**
     * Apply a theme to all blocks, mapping theme keys to block props.
     *
     * @param  list<array{id: string, type: string, props: array<string, mixed>}>  $blocks
     * @param  array<string, mixed>  $theme
     * @return list<array{id: string, type: string, props: array<string, mixed>}>
     */
    public function apply(array $blocks, array $theme): array
    {
        return array_map(fn (array $block) => $this->applyToBlock($block, $theme), $blocks);
    }

    /**
     * Resolve theme references (@variable) in settings.
     *
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    public function resolveReferences(array $settings): array
    {
        $resolved = [];

        foreach ($settings as $key => $value) {
            if (is_string($value) && str_starts_with($value, '@')) {
                $refKey = substr($value, 1);
                $resolved[$key] = $settings[$refKey] ?? $value;
            } else {
                $resolved[$key] = $value;
            }
        }

        return $resolved;
    }

    /** @return array<string, array<string, string>> */
    protected function getThemeMapping(): array
    {
        return [
            'button' => [
                'bg_color' => 'button_bg',
                'text_color' => 'button_text',
            ],
            'hero' => [
                'bg_color' => 'primary_color',
                'cta_bg_color' => 'button_bg',
                'cta_text_color' => 'button_text',
            ],
            'heading' => [
                'color' => 'text_color',
            ],
            'paragraph' => [
                'color' => 'text_color',
            ],
            'product-card' => [
                'cta_bg_color' => 'button_bg',
            ],
            'coupon' => [
                'border_color' => 'accent_color',
            ],
            'footer' => [
                'text_color' => 'muted_color',
            ],
            'alert' => [],
            'divider' => [],
        ];
    }

    /**
     * @param  array{id: string, type: string, props: array<string, mixed>}  $block
     * @param  array<string, mixed>  $theme
     * @return array{id: string, type: string, props: array<string, mixed>}
     */
    protected function applyToBlock(array $block, array $theme): array
    {
        $mapping = $this->getThemeMapping();
        $type = $block['type'];
        $propMapping = $mapping[$type] ?? [];

        foreach ($propMapping as $propKey => $themeKey) {
            if (isset($theme[$themeKey])) {
                $block['props'][$propKey] = $theme[$themeKey];
            }
        }

        if (isset($theme['font_family'])) {
            if (in_array($type, ['heading', 'paragraph', 'button', 'hero'])) {
                $block['props']['font_family'] = $theme['font_family'];
            }
        }

        return $block;
    }
}
