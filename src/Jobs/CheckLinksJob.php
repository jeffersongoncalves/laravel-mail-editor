<?php

namespace JeffersonGoncalves\MailEditor\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JeffersonGoncalves\MailEditor\Support\LinkChecker;

class CheckLinksJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 1;

    public int $timeout = 120;

    /**
     * @param  list<array{type: string, props: array<string, mixed>}>  $blocks
     */
    public function __construct(
        protected string $cacheKey,
        protected array $blocks,
    ) {}

    public function handle(): void
    {
        $checker = new LinkChecker;
        $urls = $checker->extractUrls($this->blocks);

        if (empty($urls)) {
            Cache::put($this->cacheKey, ['status' => 'completed', 'results' => []], 600);

            return;
        }

        Cache::put($this->cacheKey, ['status' => 'processing', 'results' => []], 600);

        $results = [];

        // Process in batches of 5 using Http::pool
        $batches = array_chunk($urls, 5);

        foreach ($batches as $batch) {
            $responses = Http::pool(function ($pool) use ($batch) {
                foreach ($batch as $i => $urlInfo) {
                    $url = $urlInfo['url'];

                    if (str_contains($url, '{{') || str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
                        continue;
                    }

                    $pool->as((string) $i)
                        ->timeout(10)
                        ->withOptions(['allow_redirects' => ['max' => 5]])
                        ->head($url);
                }
            });

            foreach ($batch as $i => $urlInfo) {
                $url = $urlInfo['url'];

                if (str_contains($url, '{{')) {
                    $results[] = ['url' => $url, 'status' => 'skipped', 'message' => 'Contains template variable.', 'block_type' => $urlInfo['block_type']];

                    continue;
                }

                if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
                    $results[] = ['url' => $url, 'status' => 'warning', 'message' => 'Development URL.', 'block_type' => $urlInfo['block_type']];

                    continue;
                }

                $key = (string) $i;

                if (! isset($responses[$key])) {
                    $results[] = ['url' => $url, 'status' => 'error', 'message' => 'No response received.', 'block_type' => $urlInfo['block_type']];

                    continue;
                }

                try {
                    $response = $responses[$key];

                    if ($response instanceof \Throwable) {
                        $results[] = ['url' => $url, 'status' => 'error', 'message' => 'Connection failed: '.Str::limit($response->getMessage(), 80), 'block_type' => $urlInfo['block_type']];

                        continue;
                    }

                    $statusCode = $response->status();
                    $status = ($statusCode >= 200 && $statusCode < 400) ? 'ok' : 'error';
                    $message = ($status === 'ok') ? "HTTP {$statusCode} — Link is working." : "HTTP {$statusCode} — Link may be broken.";

                    $results[] = ['url' => $url, 'status' => $status, 'message' => $message, 'block_type' => $urlInfo['block_type']];
                } catch (\Throwable $e) {
                    $results[] = ['url' => $url, 'status' => 'error', 'message' => 'Check failed: '.Str::limit($e->getMessage(), 80), 'block_type' => $urlInfo['block_type']];
                }
            }
        }

        Cache::put($this->cacheKey, ['status' => 'completed', 'results' => $results], 600);
    }

    /**
     * Generate a cache key for a template's link check.
     */
    public static function cacheKey(int $templateId): string
    {
        return "mail-editor:link-check:{$templateId}";
    }
}
