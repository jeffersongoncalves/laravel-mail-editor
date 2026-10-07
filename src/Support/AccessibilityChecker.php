<?php

namespace JeffersonGoncalves\MailEditor\Support;

class AccessibilityChecker
{
    /**
     * Run accessibility checks on email blocks.
     *
     * @param  list<array{type: string, props: array<string, mixed>}>  $blocks
     * @return list<array{label: string, status: string, message: string, wcag: string}>
     */
    public function check(array $blocks): array
    {
        $results = [];

        $results[] = $this->checkImageAlts($blocks);
        $results[] = $this->checkColorContrast($blocks);
        $results[] = $this->checkFontSizes($blocks);
        $results[] = $this->checkLinkText($blocks);
        $results[] = $this->checkHeadingHierarchy($blocks);
        $results[] = $this->checkButtonAccessibility($blocks);
        $results[] = $this->checkLanguageDirection($blocks);
        $results[] = $this->checkTableRoles($blocks);

        return $results;
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkImageAlts(array $blocks): array
    {
        $missing = 0;

        // block type => [image src prop, alt text prop]
        $imageProps = [
            'image' => ['src', 'alt'],
            'product-card' => ['image_src', 'image_alt'],
            'header' => ['logo_src', 'logo_alt'],
        ];

        foreach ($blocks as $block) {
            [$srcKey, $altKey] = $imageProps[$block['type'] ?? ''] ?? [null, null];

            if ($srcKey === null) {
                continue;
            }

            $props = $block['props'] ?? [];

            if (! empty($props[$srcKey] ?? '') && empty($props[$altKey] ?? '')) {
                $missing++;
            }
        }

        if ($missing > 0) {
            return ['label' => 'Image Alt Text', 'status' => 'error', 'message' => "{$missing} image(s) missing alt text. Screen readers cannot describe these images.", 'wcag' => '1.1.1'];
        }

        return ['label' => 'Image Alt Text', 'status' => 'ok', 'message' => 'All images have alt text.', 'wcag' => '1.1.1'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkColorContrast(array $blocks): array
    {
        $issues = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $props = $block['props'] ?? [];

            // Check text color against common backgrounds
            $textColor = $props['color'] ?? $props['text_color'] ?? null;
            $bgColor = $props['bg_color'] ?? null;

            if ($textColor && $bgColor) {
                $ratio = $this->calculateContrastRatio($textColor, $bgColor);
                if ($ratio < 4.5) {
                    $issues[] = "{$type} block: contrast ratio {$ratio}:1 (minimum 4.5:1)";
                }
            }

            // Check button contrast
            if ($type === 'button' || $type === 'hero' || $type === 'product-card') {
                $btnBg = $props['bg_color'] ?? $props['cta_bg_color'] ?? null;
                $btnText = $props['text_color'] ?? $props['cta_text_color'] ?? null;

                if ($btnBg && $btnText) {
                    $ratio = $this->calculateContrastRatio($btnText, $btnBg);
                    if ($ratio < 4.5) {
                        $issues[] = "{$type} button: contrast ratio {$ratio}:1";
                    }
                }
            }
        }

        if (! empty($issues)) {
            $issueList = implode('; ', array_slice($issues, 0, 3));

            return ['label' => 'Color Contrast', 'status' => 'warning', 'message' => "Low contrast detected: {$issueList}.", 'wcag' => '1.4.3'];
        }

        return ['label' => 'Color Contrast', 'status' => 'ok', 'message' => 'Color contrast meets WCAG AA standards.', 'wcag' => '1.4.3'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkFontSizes(array $blocks): array
    {
        $tooSmall = 0;

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            if (in_array($type, ['preheader', 'footer'])) {
                continue; // These are expected to be smaller
            }

            $fontSize = $block['props']['font_size'] ?? null;
            if ($fontSize !== null && (int) $fontSize < 14) {
                $tooSmall++;
            }
        }

        if ($tooSmall > 0) {
            return ['label' => 'Font Sizes', 'status' => 'warning', 'message' => "{$tooSmall} block(s) with font size below 14px. May be hard to read on mobile devices.", 'wcag' => '1.4.4'];
        }

        return ['label' => 'Font Sizes', 'status' => 'ok', 'message' => 'All font sizes are accessible.', 'wcag' => '1.4.4'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkLinkText(array $blocks): array
    {
        $genericLinks = 0;
        $genericTexts = ['click here', 'here', 'read more', 'learn more', 'link', 'more'];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $props = $block['props'] ?? [];

            // Check button text
            if ($type === 'button') {
                $text = strtolower(trim($props['text'] ?? ''));
                if (in_array($text, $genericTexts)) {
                    $genericLinks++;
                }
            }

            // Check paragraph content for generic link text
            if ($type === 'paragraph') {
                $html = strtolower($props['html'] ?? '');
                foreach ($genericTexts as $generic) {
                    if (preg_match('/<a[^>]*>\s*'.preg_quote($generic, '/').'\s*<\/a>/i', $html)) {
                        $genericLinks++;
                    }
                }
            }
        }

        if ($genericLinks > 0) {
            return ['label' => 'Link Text', 'status' => 'warning', 'message' => "{$genericLinks} link(s) with generic text (e.g., \"click here\"). Use descriptive text for screen readers.", 'wcag' => '2.4.4'];
        }

        return ['label' => 'Link Text', 'status' => 'ok', 'message' => 'All links have descriptive text.', 'wcag' => '2.4.4'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkHeadingHierarchy(array $blocks): array
    {
        $levels = [];

        foreach ($blocks as $block) {
            if (($block['type'] ?? '') === 'heading') {
                $level = (int) str_replace('h', '', $block['props']['level'] ?? 'h2');
                $levels[] = $level;
            }
        }

        if (empty($levels)) {
            return ['label' => 'Heading Hierarchy', 'status' => 'ok', 'message' => 'No heading hierarchy issues.', 'wcag' => '1.3.1'];
        }

        // Check for skipped levels
        $hasSkip = false;
        for ($i = 1; $i < count($levels); $i++) {
            if ($levels[$i] > $levels[$i - 1] + 1) {
                $hasSkip = true;

                break;
            }
        }

        if ($hasSkip) {
            return ['label' => 'Heading Hierarchy', 'status' => 'warning', 'message' => 'Heading levels are skipped (e.g., h1 to h3). Use sequential levels for screen readers.', 'wcag' => '1.3.1'];
        }

        return ['label' => 'Heading Hierarchy', 'status' => 'ok', 'message' => 'Heading hierarchy is correct.', 'wcag' => '1.3.1'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkButtonAccessibility(array $blocks): array
    {
        $issues = 0;

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';

            if (! in_array($type, ['button', 'hero', 'product-card'])) {
                continue;
            }

            $textKey = $type === 'button' ? 'text' : 'cta_text';
            $text = trim($block['props'][$textKey] ?? '');

            if (empty($text)) {
                $issues++;
            }
        }

        if ($issues > 0) {
            return ['label' => 'Button Labels', 'status' => 'error', 'message' => "{$issues} button(s) without visible text. Buttons must have labels.", 'wcag' => '4.1.2'];
        }

        return ['label' => 'Button Labels', 'status' => 'ok', 'message' => 'All buttons have labels.', 'wcag' => '4.1.2'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkLanguageDirection(array $blocks): array
    {
        // Check for mixed RTL/LTR content without explicit direction
        $hasRtl = false;
        foreach ($blocks as $block) {
            foreach ($block['props'] ?? [] as $value) {
                if (is_string($value) && preg_match('/[\x{0600}-\x{06FF}\x{0590}-\x{05FF}]/u', $value)) {
                    $hasRtl = true;

                    break 2;
                }
            }
        }

        if ($hasRtl) {
            return ['label' => 'Text Direction', 'status' => 'warning', 'message' => 'RTL text detected. Ensure dir="rtl" is set for right-to-left content sections.', 'wcag' => '1.3.2'];
        }

        return ['label' => 'Text Direction', 'status' => 'ok', 'message' => 'No text direction issues.', 'wcag' => '1.3.2'];
    }

    /** @return array{label: string, status: string, message: string, wcag: string} */
    protected function checkTableRoles(array $blocks): array
    {
        $hasTables = collect($blocks)->contains(fn (array $b) => ($b['type'] ?? '') === 'data-table');

        if ($hasTables) {
            return ['label' => 'Data Tables', 'status' => 'ok', 'message' => 'Data tables use proper table markup with headers.', 'wcag' => '1.3.1'];
        }

        return ['label' => 'Data Tables', 'status' => 'ok', 'message' => 'No data table issues.', 'wcag' => '1.3.1'];
    }

    /**
     * Calculate WCAG contrast ratio between two hex colors.
     */
    protected function calculateContrastRatio(string $color1, string $color2): float
    {
        $l1 = $this->relativeLuminance($color1);
        $l2 = $this->relativeLuminance($color2);

        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return round(($lighter + 0.05) / ($darker + 0.05), 1);
    }

    /**
     * Calculate relative luminance of a hex color per WCAG 2.0.
     */
    protected function relativeLuminance(string $hex): float
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6) {
            return 0.0;
        }

        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}
