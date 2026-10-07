<?php

namespace JeffersonGoncalves\MailEditor\Support;

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class SpamScoreAnalyzer
{
    /** @var list<string> */
    protected const SPAM_WORDS_HIGH = [
        'act now', 'apply now', 'buy direct', 'buy now', 'click below',
        'click here', 'click now', 'direct email', 'don\'t delete',
        'don\'t miss', 'exclusive deal', 'expire', 'free', 'grátis',
        'gratuito', 'great offer', 'increase', 'incredible deal',
        'limited time', 'new customers only', 'no obligation',
        'offer expires', 'once in a lifetime', 'order now', 'please read',
        'special promotion', 'urgent', 'what are you waiting for',
        'while supplies last', 'winner', 'you have been selected',
    ];

    /** @var list<string> */
    protected const SPAM_WORDS_MEDIUM = [
        '100%', 'additional income', 'be your own boss', 'cash',
        'congratulations', 'credit', 'discount', 'double your',
        'earn extra', 'extra cash', 'fast cash', 'financial freedom',
        'guarantee', 'income', 'investment', 'lowest price',
        'luxury', 'money back', 'no catch', 'no cost', 'no fees',
        'no strings', 'obligation', 'prize', 'profit', 'promise',
        'risk free', 'satisfaction', 'save big', 'save up to',
        'special offer', 'trial', 'unlimited', 'unsecured',
    ];

    /**
     * Calculate a deliverability score (0-100) and return detailed analysis.
     *
     * @return array{score: int, checks: list<array{label: string, status: string, message: string, points: int}>}
     */
    public function analyze(EmailTemplate $template): array
    {
        $blocks = $template->blocks ?? [];
        $subject = $template->subject ?? '';
        $checks = [];
        $totalPoints = 100;

        $checks[] = $this->checkTextToImageRatio($blocks, $totalPoints);
        $checks[] = $this->checkSpamWords($blocks, $subject, $totalPoints);
        $checks[] = $this->checkExclamationMarks($blocks, $subject, $totalPoints);
        $checks[] = $this->checkAllCaps($blocks, $subject, $totalPoints);
        $checks[] = $this->checkSubjectLength($subject, $totalPoints);
        $checks[] = $this->checkPreheader($blocks, $totalPoints);
        $checks[] = $this->checkUnsubscribeLink($blocks, $totalPoints);
        $checks[] = $this->checkImageAlts($blocks, $totalPoints);
        $checks[] = $this->checkHtmlSize($blocks, $totalPoints);
        $checks[] = $this->checkUrlPatterns($blocks, $totalPoints);
        $checks[] = $this->checkFontColors($blocks, $totalPoints);
        $checks[] = $this->checkLinkDensity($blocks, $totalPoints);

        $deductions = array_sum(array_column($checks, 'points'));
        $score = max(0, 100 + $deductions); // deductions are negative

        return [
            'score' => $score,
            'checks' => $checks,
        ];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkTextToImageRatio(array $blocks, int &$total): array
    {
        $textLength = 0;
        $imageCount = 0;

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $props = $block['props'] ?? [];

            if (in_array($type, ['paragraph', 'heading', 'alert', 'testimonial', 'list'])) {
                $textLength += mb_strlen(strip_tags((string) ($props['html'] ?? $props['text'] ?? '')));
            }

            if (in_array($type, ['image', 'hero', 'product-card', 'video-thumb', 'logo-grid'])) {
                $imageCount++;
            }
        }

        if ($textLength === 0 && $imageCount > 0) {
            return ['label' => 'Text/Image Ratio', 'status' => 'error', 'message' => 'Email is image-only. Add text content — image-only emails are flagged by spam filters.', 'points' => -25];
        }

        if ($imageCount > 0 && $textLength < 100) {
            return ['label' => 'Text/Image Ratio', 'status' => 'warning', 'message' => 'Very low text-to-image ratio. Recommended: at least 60% text, 40% images.', 'points' => -10];
        }

        return ['label' => 'Text/Image Ratio', 'status' => 'ok', 'message' => 'Good text-to-image ratio.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkSpamWords(array $blocks, string $subject, int &$total): array
    {
        $allText = mb_strtolower($subject);

        foreach ($blocks as $block) {
            foreach ($block['props'] ?? [] as $value) {
                if (is_string($value)) {
                    $allText .= ' '.mb_strtolower($value);
                }
            }
        }

        $highFound = [];
        foreach (self::SPAM_WORDS_HIGH as $word) {
            if (str_contains($allText, $word)) {
                $highFound[] = $word;
            }
        }

        $mediumFound = [];
        foreach (self::SPAM_WORDS_MEDIUM as $word) {
            if (str_contains($allText, $word)) {
                $mediumFound[] = $word;
            }
        }

        $totalSpam = count($highFound) + count($mediumFound);

        if ($totalSpam === 0) {
            return ['label' => 'Spam Words', 'status' => 'ok', 'message' => 'No spam trigger words detected.', 'points' => 0];
        }

        $points = -(count($highFound) * 5 + count($mediumFound) * 2);
        $points = max($points, -30);

        $wordList = implode(', ', array_slice(array_merge($highFound, $mediumFound), 0, 5));
        $status = $totalSpam > 5 ? 'error' : 'warning';

        return ['label' => 'Spam Words', 'status' => $status, 'message' => "Found {$totalSpam} spam trigger word(s): {$wordList}.", 'points' => $points];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkExclamationMarks(array $blocks, string $subject, int &$total): array
    {
        $allText = $subject;
        foreach ($blocks as $block) {
            foreach ($block['props'] ?? [] as $value) {
                if (is_string($value)) {
                    $allText .= ' '.$value;
                }
            }
        }

        $count = substr_count($allText, '!');

        if ($count > 10) {
            return ['label' => 'Exclamation Marks', 'status' => 'error', 'message' => "Excessive exclamation marks ({$count}). This is a strong spam signal.", 'points' => -15];
        }

        if ($count > 5) {
            return ['label' => 'Exclamation Marks', 'status' => 'warning', 'message' => "Multiple exclamation marks ({$count}). Try to reduce them.", 'points' => -5];
        }

        return ['label' => 'Exclamation Marks', 'status' => 'ok', 'message' => 'Exclamation usage is reasonable.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkAllCaps(array $blocks, string $subject, int &$total): array
    {
        $allText = $subject;
        foreach ($blocks as $block) {
            foreach ($block['props'] ?? [] as $value) {
                if (is_string($value)) {
                    $allText .= ' '.$value;
                }
            }
        }

        // Count words that are ALL CAPS (3+ chars)
        preg_match_all('/\b[A-Z]{3,}\b/', $allText, $matches);
        $capsCount = count($matches[0]);

        if ($capsCount > 10) {
            return ['label' => 'ALL CAPS', 'status' => 'error', 'message' => "Excessive ALL CAPS words ({$capsCount}). Major spam trigger.", 'points' => -15];
        }

        if ($capsCount > 3) {
            return ['label' => 'ALL CAPS', 'status' => 'warning', 'message' => "Multiple ALL CAPS words ({$capsCount}). Reduce usage.", 'points' => -5];
        }

        return ['label' => 'ALL CAPS', 'status' => 'ok', 'message' => 'No excessive capitalization.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkSubjectLength(string $subject, int &$total): array
    {
        $len = mb_strlen($subject);

        if ($len === 0) {
            return ['label' => 'Subject Line', 'status' => 'error', 'message' => 'No subject line. This will likely be flagged as spam.', 'points' => -20];
        }

        if ($len < 20) {
            return ['label' => 'Subject Line', 'status' => 'warning', 'message' => "Subject too short ({$len} chars). Recommended: 20-60 characters.", 'points' => -5];
        }

        if ($len > 60) {
            return ['label' => 'Subject Line', 'status' => 'warning', 'message' => "Subject too long ({$len} chars). May be truncated on mobile.", 'points' => -3];
        }

        return ['label' => 'Subject Line', 'status' => 'ok', 'message' => 'Subject line length is good.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkPreheader(array $blocks, int &$total): array
    {
        $preheader = collect($blocks)->first(fn (array $b) => ($b['type'] ?? '') === 'preheader');

        if (! $preheader) {
            return ['label' => 'Preheader', 'status' => 'warning', 'message' => 'No preheader text. Adding one improves open rates.', 'points' => -3];
        }

        $text = $preheader['props']['text'] ?? '';
        $len = mb_strlen($text);

        if ($len < 30 || $len > 90) {
            return ['label' => 'Preheader', 'status' => 'warning', 'message' => "Preheader length ({$len} chars) outside optimal range (30-90).", 'points' => -2];
        }

        return ['label' => 'Preheader', 'status' => 'ok', 'message' => 'Preheader looks good.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkUnsubscribeLink(array $blocks, int &$total): array
    {
        $footer = collect($blocks)->first(fn (array $b) => ($b['type'] ?? '') === 'footer');

        if (! $footer) {
            return ['label' => 'Unsubscribe', 'status' => 'error', 'message' => 'No footer with unsubscribe link. Required by CAN-SPAM/LGPD.', 'points' => -20];
        }

        if (empty($footer['props']['unsubscribe_url'] ?? '')) {
            return ['label' => 'Unsubscribe', 'status' => 'error', 'message' => 'Footer has no unsubscribe URL.', 'points' => -15];
        }

        return ['label' => 'Unsubscribe', 'status' => 'ok', 'message' => 'Unsubscribe link present.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkImageAlts(array $blocks, int &$total): array
    {
        $missing = 0;

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $props = $block['props'] ?? [];

            if ($type === 'image' && empty($props['alt'] ?? '')) {
                $missing++;
            }
            if ($type === 'product-card' && empty($props['image_alt'] ?? '') && ! empty($props['image_src'] ?? '')) {
                $missing++;
            }
        }

        if ($missing > 0) {
            return ['label' => 'Image Alt Text', 'status' => 'warning', 'message' => "{$missing} image(s) without alt text.", 'points' => -($missing * 2)];
        }

        return ['label' => 'Image Alt Text', 'status' => 'ok', 'message' => 'All images have alt text.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkHtmlSize(array $blocks, int &$total): array
    {
        $estimatedSize = strlen((string) json_encode($blocks)) * 1.5;

        if ($estimatedSize > 102400) {
            return ['label' => 'Email Size', 'status' => 'error', 'message' => 'Template may exceed 100KB. Gmail clips larger emails.', 'points' => -10];
        }

        if ($estimatedSize > 80000) {
            return ['label' => 'Email Size', 'status' => 'warning', 'message' => 'Template approaching 100KB limit.', 'points' => -3];
        }

        return ['label' => 'Email Size', 'status' => 'ok', 'message' => 'Email size is within limits.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkUrlPatterns(array $blocks, int &$total): array
    {
        $issues = [];

        foreach ($blocks as $block) {
            $props = $block['props'] ?? [];
            foreach ($props as $key => $value) {
                if (! is_string($value) || ! str_starts_with($value, 'http')) {
                    continue;
                }

                // URL shorteners are suspicious
                if (preg_match('/\b(bit\.ly|tinyurl|goo\.gl|t\.co|ow\.ly|is\.gd)\b/i', $value)) {
                    $issues[] = 'URL shortener detected';
                }

                // IP-based URLs
                if (preg_match('/https?:\/\/\d+\.\d+\.\d+\.\d+/', $value)) {
                    $issues[] = 'IP-based URL detected';
                }
            }
        }

        if (! empty($issues)) {
            $issueList = implode(', ', array_unique($issues));

            return ['label' => 'URL Patterns', 'status' => 'warning', 'message' => "Suspicious URL patterns: {$issueList}.", 'points' => -(count($issues) * 5)];
        }

        return ['label' => 'URL Patterns', 'status' => 'ok', 'message' => 'All URLs look clean.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkFontColors(array $blocks, int &$total): array
    {
        foreach ($blocks as $block) {
            $props = $block['props'] ?? [];
            $color = $props['color'] ?? $props['text_color'] ?? null;

            if (! is_string($color)) {
                continue;
            }

            $color = strtolower($color);

            // Hidden text (white on white, very light colors)
            if (in_array($color, ['#ffffff', '#fff', '#fefefe', '#fafafa'])) {
                if (($block['type'] ?? '') !== 'preheader') {
                    return ['label' => 'Hidden Text', 'status' => 'warning', 'message' => 'Detected near-white font color. This can trigger spam filters.', 'points' => -10];
                }
            }
        }

        return ['label' => 'Hidden Text', 'status' => 'ok', 'message' => 'No hidden text detected.', 'points' => 0];
    }

    /** @return array{label: string, status: string, message: string, points: int} */
    protected function checkLinkDensity(array $blocks, int &$total): array
    {
        $linkCount = 0;
        $totalBlocks = count($blocks);

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $props = $block['props'] ?? [];

            if (in_array($type, ['button', 'hero', 'product-card'])) {
                $linkCount++;
            }

            // Check for links in paragraph HTML
            if ($type === 'paragraph') {
                $html = $props['html'] ?? '';
                $linkCount += substr_count(strtolower($html), '<a ');
            }
        }

        if ($linkCount > 15) {
            return ['label' => 'Link Density', 'status' => 'warning', 'message' => "High number of links ({$linkCount}). Excessive links can trigger spam filters.", 'points' => -5];
        }

        return ['label' => 'Link Density', 'status' => 'ok', 'message' => 'Link density is reasonable.', 'points' => 0];
    }
}
