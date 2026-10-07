<?php

namespace JeffersonGoncalves\MailEditor\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use JeffersonGoncalves\MailEditor\Support\BlockRegistry;

class PreviewController
{
    public function __invoke(Request $request, BlockRegistry $registry): View
    {
        $blocks = json_decode($request->query('blocks', '[]'), true) ?? [];
        $settings = json_decode($request->query('settings', '{}'), true) ?? [];
        $client = $request->query('client', 'gmail');

        $mediaQueries = collect($blocks)
            ->map(fn ($block) => $registry->find($block['type'] ?? '')?->getMediaQueries())
            ->filter()
            ->unique()
            ->join("\n");

        $blocksHtml = collect($blocks)->map(function ($block) use ($registry) {
            $instance = $registry->find($block['type'] ?? '');

            return $instance?->render($block['props'] ?? []) ?? '';
        })->join("\n");

        return view('mail-editor::preview.frame', [
            'content' => $blocksHtml,
            'settings' => $settings,
            'client' => $client,
            'mediaQueries' => $mediaQueries,
        ]);
    }
}
