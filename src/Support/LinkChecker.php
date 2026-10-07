<?php

namespace JeffersonGoncalves\MailEditor\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LinkChecker
{
    protected int $timeout = 10;

    /**
     * Check all URLs in template blocks.
     *
     * @param  list<array{type: string, props: array<string, mixed>}>  $blocks
     * @return list<array{url: string, status: string, message: string, block_type: string}>
     */
    public function check(array $blocks): array
    {
        $urls = $this->extractUrls($blocks);
        $results = [];

        foreach ($urls as $urlInfo) {
            $results[] = $this->checkUrl($urlInfo['url'], $urlInfo['block_type']);
        }

        return $results;
    }

    /**
     * Extract all URLs from blocks.
     *
     * @return list<array{url: string, block_type: string}>
     */
    public function extractUrls(array $blocks): array
    {
        $urls = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $props = $block['props'] ?? [];

            $urlKeys = match ($type) {
                'button' => ['url'],
                'hero' => ['cta_url'],
                'product-card' => ['cta_url'],
                'image' => ['link', 'src'],
                'video-thumb' => ['url', 'thumb_src'],
                'header' => ['web_link', 'logo_src'],
                'footer' => ['unsubscribe_url', 'web_version_url'],
                'coupon' => ['cta_url'],
                default => [],
            };

            foreach ($urlKeys as $key) {
                $url = $props[$key] ?? '';
                if (! empty($url) && str_starts_with($url, 'http')) {
                    $urls[] = ['url' => $url, 'block_type' => $type];
                }
            }

            // Extract URLs from paragraph HTML
            if ($type === 'paragraph') {
                $html = $props['html'] ?? '';
                preg_match_all('/href=["\']([^"\']+)["\']/i', $html, $matches);
                foreach ($matches[1] as $match) {
                    if (str_starts_with($match, 'http')) {
                        $urls[] = ['url' => $match, 'block_type' => 'paragraph'];
                    }
                }
            }

            // Extract URLs from footer social links
            if ($type === 'footer' && isset($props['social_links']) && is_array($props['social_links'])) {
                foreach ($props['social_links'] as $link) {
                    $socialUrl = $link['url'] ?? '';
                    if (! empty($socialUrl) && str_starts_with($socialUrl, 'http')) {
                        $urls[] = ['url' => $socialUrl, 'block_type' => 'footer (social)'];
                    }
                }
            }
        }

        // Deduplicate by URL
        $seen = [];
        $unique = [];
        foreach ($urls as $urlInfo) {
            if (! isset($seen[$urlInfo['url']])) {
                $seen[$urlInfo['url']] = true;
                $unique[] = $urlInfo;
            }
        }

        return $unique;
    }

    /**
     * Check a single URL.
     *
     * @return array{url: string, status: string, message: string, block_type: string}
     */
    protected function checkUrl(string $url, string $blockType): array
    {
        // Skip variable URLs
        if (str_contains($url, '{{')) {
            return [
                'url' => $url,
                'status' => 'skipped',
                'message' => 'Contains template variable — cannot check.',
                'block_type' => $blockType,
            ];
        }

        // Skip localhost/development URLs
        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            return [
                'url' => $url,
                'status' => 'warning',
                'message' => 'Development URL — will not work in production.',
                'block_type' => $blockType,
            ];
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withOptions(['allow_redirects' => ['max' => 5, 'track_redirects' => true]])
                ->head($url);

            $statusCode = $response->status();

            if ($statusCode >= 200 && $statusCode < 400) {
                return [
                    'url' => $url,
                    'status' => 'ok',
                    'message' => "HTTP {$statusCode} — Link is working.",
                    'block_type' => $blockType,
                ];
            }

            if ($statusCode === 405) {
                // Method not allowed for HEAD, try GET
                $response = Http::timeout($this->timeout)->get($url);
                $statusCode = $response->status();

                if ($statusCode >= 200 && $statusCode < 400) {
                    return [
                        'url' => $url,
                        'status' => 'ok',
                        'message' => "HTTP {$statusCode} — Link is working.",
                        'block_type' => $blockType,
                    ];
                }
            }

            return [
                'url' => $url,
                'status' => 'error',
                'message' => "HTTP {$statusCode} — Link may be broken.",
                'block_type' => $blockType,
            ];
        } catch (\Throwable $e) {
            return [
                'url' => $url,
                'status' => 'error',
                'message' => 'Connection failed: '.Str::limit($e->getMessage(), 80),
                'block_type' => $blockType,
            ];
        }
    }
}
