<?php

namespace JeffersonGoncalves\MailEditor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class CountdownController
{
    public function __invoke(Request $request): Response
    {
        $tz = (string) $request->query('tz', 'America/Sao_Paulo');
        $width = min((int) $request->query('w', '500'), 800);
        $height = min((int) $request->query('h', '80'), 200);
        $style = (string) $request->query('style', 'default');
        $expiredText = (string) $request->query('expired', 'Offer expired');
        $now = now($tz);

        try {
            $endRaw = (string) $request->query('end', '');
            $endDate = $endRaw !== '' ? Carbon::parse($endRaw, $tz) : null;
        } catch (\Throwable) {
            $endDate = null;
        }

        if ($endDate === null || $now >= $endDate) {
            return $this->renderImage($expiredText, $width, $height, $style, true);
        }

        $diff = $now->diff($endDate);
        $days = (int) $diff->format('%a');
        $hours = (int) $diff->format('%H');
        $minutes = (int) $diff->format('%I');
        $seconds = (int) $diff->format('%S');

        $text = sprintf('%dd %02dh %02dm %02ds', $days, $hours, $minutes, $seconds);

        return $this->renderImage($text, $width, $height, $style, false);
    }

    protected function renderImage(string $text, int $width, int $height, string $style, bool $expired): Response
    {
        $colors = match ($style) {
            'dark' => ['bg' => [30, 30, 30], 'text' => [255, 255, 255], 'accent' => [239, 159, 39]],
            'minimal' => ['bg' => [255, 255, 255], 'text' => [51, 51, 51], 'accent' => [100, 100, 100]],
            default => ['bg' => [55, 138, 221], 'text' => [255, 255, 255], 'accent' => [255, 243, 205]],
        };

        $image = imagecreatetruecolor($width, $height);

        $bgColor = imagecolorallocate($image, ...$colors['bg']);
        $textColor = imagecolorallocate($image, ...($expired ? $colors['accent'] : $colors['text']));

        imagefill($image, 0, 0, $bgColor);

        $fontSize = max(5, min((int) ($height * 0.35), 5));
        $fontSize = min($fontSize, 5);

        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = (int) (($width - $textWidth) / 2);
        $y = (int) (($height - $textHeight) / 2);

        imagestring($image, $fontSize, max(0, $x), max(0, $y), $text, $textColor);

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return new Response($imageData, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }
}
