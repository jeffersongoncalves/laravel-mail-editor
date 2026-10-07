<?php

namespace JeffersonGoncalves\MailEditor\Support;

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class QualityChecker
{
    /** @var list<string> */
    protected const SPAM_WORDS = [
        'free', 'grátis', 'gratuito', '100%', 'act now', 'limited time',
        'click here', 'buy now', 'order now', 'don\'t miss', 'urgent',
        'congratulations', 'winner', 'prize', 'cash', 'guarantee',
    ];

    /**
     * @return list<array{label: string, status: string, message: string}>
     */
    public function check(EmailTemplate $template): array
    {
        $blocks = $template->blocks ?? [];
        $results = [];

        $results[] = $this->checkPreheader($blocks);
        $results[] = $this->checkFooterUnsubscribe($blocks);
        $results[] = $this->checkImageAlts($blocks);
        $results[] = $this->checkSubject($template->subject ?? '');
        $results[] = $this->checkHtmlSize($blocks);
        $results[] = $this->checkButtonUrls($blocks);
        $results[] = $this->checkImagesHosted($blocks);
        $results[] = $this->checkMinFontSize($blocks);
        $results[] = $this->checkSpamWords($blocks, $template->subject ?? '');

        return $results;
    }

    /**
     * @param  list<array{type: string, props: array<string, mixed>}>  $blocks
     * @return array{label: string, status: string, message: string}
     */
    protected function checkPreheader(array $blocks): array
    {
        $preheader = collect($blocks)->first(fn (array $b) => ($b['type']) === 'preheader');

        if (! $preheader) {
            return ['label' => 'Preheader', 'status' => 'warning', 'message' => 'No preheader block found. Recommended to improve open rates.'];
        }

        $text = $preheader['props']['text'] ?? '';
        $len = mb_strlen($text);

        if ($len < 30) {
            return ['label' => 'Preheader', 'status' => 'warning', 'message' => "Preheader too short ({$len} chars). Recommended: 30-90 characters."];
        }

        if ($len > 90) {
            return ['label' => 'Preheader', 'status' => 'warning', 'message' => "Preheader too long ({$len} chars). May be truncated. Recommended: 30-90 characters."];
        }

        return ['label' => 'Preheader', 'status' => 'ok', 'message' => 'Preheader looks good.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkFooterUnsubscribe(array $blocks): array
    {
        $footer = collect($blocks)->first(fn (array $b) => ($b['type'] ?? '') === 'footer');

        if (! $footer) {
            return ['label' => 'Unsubscribe', 'status' => 'error', 'message' => 'No footer block found. Unsubscribe link is required by law (CAN-SPAM/LGPD).'];
        }

        $url = $footer['props']['unsubscribe_url'] ?? '';
        if (empty($url)) {
            return ['label' => 'Unsubscribe', 'status' => 'error', 'message' => 'Footer has no unsubscribe URL. Required by law.'];
        }

        return ['label' => 'Unsubscribe', 'status' => 'ok', 'message' => 'Unsubscribe link present.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkImageAlts(array $blocks): array
    {
        $images = collect($blocks)->filter(fn (array $b) => in_array($b['type'] ?? '', ['image', 'hero', 'product-card', 'logo-grid']));
        $missing = 0;

        foreach ($images as $block) {
            if (($block['type'] ?? '') === 'image' && empty($block['props']['alt'] ?? '')) {
                $missing++;
            }
            if (($block['type'] ?? '') === 'product-card' && empty($block['props']['image_alt'] ?? '') && ! empty($block['props']['image_src'] ?? '')) {
                $missing++;
            }
        }

        if ($missing > 0) {
            return ['label' => 'Image Alt Text', 'status' => 'warning', 'message' => "{$missing} image(s) without alt text. Required for accessibility."];
        }

        return ['label' => 'Image Alt Text', 'status' => 'ok', 'message' => 'All images have alt text.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkSubject(string $subject): array
    {
        $len = mb_strlen($subject);

        if ($len < 20) {
            return ['label' => 'Subject Line', 'status' => 'warning', 'message' => "Subject too short ({$len} chars). Recommended: 20-60 characters."];
        }

        if ($len > 60) {
            return ['label' => 'Subject Line', 'status' => 'warning', 'message' => "Subject too long ({$len} chars). May be truncated. Recommended: 20-60 characters."];
        }

        $uppercaseRatio = mb_strlen(preg_replace('/[^A-Z]/', '', $subject)) / max($len, 1);
        if ($uppercaseRatio > 0.5) {
            return ['label' => 'Subject Line', 'status' => 'warning', 'message' => 'Subject has excessive ALL CAPS. This may trigger spam filters.'];
        }

        return ['label' => 'Subject Line', 'status' => 'ok', 'message' => 'Subject line looks good.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkHtmlSize(array $blocks): array
    {
        $estimatedSize = strlen((string) json_encode($blocks)) * 1.5;

        if ($estimatedSize > 102400) {
            return ['label' => 'HTML Size', 'status' => 'warning', 'message' => 'Template may exceed 100KB. Some clients (Gmail) clip larger emails.'];
        }

        return ['label' => 'HTML Size', 'status' => 'ok', 'message' => 'Template size is within limits.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkButtonUrls(array $blocks): array
    {
        $buttons = collect($blocks)->filter(fn (array $b) => in_array($b['type'] ?? '', ['button', 'hero', 'product-card']));
        $invalid = 0;

        foreach ($buttons as $block) {
            $urlKeys = match ($block['type'] ?? '') {
                'button' => ['url'],
                'hero' => ['cta_url'],
                'product-card' => ['cta_url'],
                default => [],
            };

            foreach ($urlKeys as $key) {
                $url = $block['props'][$key] ?? '';
                if ($url === '#' || $url === '' || $url === 'http://' || $url === 'https://') {
                    $invalid++;
                }
            }
        }

        if ($invalid > 0) {
            return ['label' => 'Button URLs', 'status' => 'error', 'message' => "{$invalid} button(s) with empty or placeholder URL (#)."];
        }

        return ['label' => 'Button URLs', 'status' => 'ok', 'message' => 'All buttons have valid URLs.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkImagesHosted(array $blocks): array
    {
        $issues = 0;

        foreach ($blocks as $block) {
            $srcKeys = match ($block['type'] ?? '') {
                'image' => ['src'],
                'hero' => ['bg_image'],
                'product-card' => ['image_src'],
                'video-thumb' => ['thumb_src'],
                'header' => ['logo_src'],
                default => [],
            };

            foreach ($srcKeys as $key) {
                $src = $block['props'][$key] ?? '';
                if (empty($src)) {
                    continue;
                }
                if (str_contains($src, 'localhost') || str_contains($src, '127.0.0.1') || ! str_starts_with($src, 'http')) {
                    $issues++;
                }
            }
        }

        if ($issues > 0) {
            return ['label' => 'Images Hosted', 'status' => 'error', 'message' => "{$issues} image(s) using localhost or relative URLs. Must use publicly hosted URLs."];
        }

        return ['label' => 'Images Hosted', 'status' => 'ok', 'message' => 'All images use hosted URLs.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkMinFontSize(array $blocks): array
    {
        $small = 0;

        foreach ($blocks as $block) {
            $fontSize = $block['props']['font_size'] ?? null;
            if ($fontSize !== null && (int) $fontSize < 13 && ! in_array($block['type'] ?? '', ['footer', 'preheader'])) {
                $small++;
            }
        }

        if ($small > 0) {
            return ['label' => 'Font Size', 'status' => 'warning', 'message' => "{$small} block(s) with font size below 13px. May be hard to read on mobile."];
        }

        return ['label' => 'Font Size', 'status' => 'ok', 'message' => 'All font sizes are readable.'];
    }

    /** @return array{label: string, status: string, message: string} */
    protected function checkSpamWords(array $blocks, string $subject): array
    {
        $allText = mb_strtolower($subject);

        foreach ($blocks as $block) {
            foreach ($block['props'] ?? [] as $value) {
                if (is_string($value)) {
                    $allText .= ' '.mb_strtolower($value);
                }
            }
        }

        $found = [];
        foreach (self::SPAM_WORDS as $word) {
            if (str_contains($allText, $word)) {
                $found[] = $word;
            }
        }

        $exclamationCount = substr_count($allText, '!');

        if (count($found) > 3 || $exclamationCount > 5) {
            $wordList = implode(', ', array_slice($found, 0, 5));

            return ['label' => 'Spam Score', 'status' => 'warning', 'message' => "Potential spam triggers detected: {$wordList}. Excessive use may affect deliverability."];
        }

        return ['label' => 'Spam Score', 'status' => 'ok', 'message' => 'No significant spam triggers detected.'];
    }
}
