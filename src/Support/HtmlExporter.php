<?php

namespace JeffersonGoncalves\MailEditor\Support;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class HtmlExporter
{
    public function __construct(
        protected BlockRegistry $registry,
    ) {}

    public function export(array $blocks, array $settings = []): string
    {
        $themeApplier = new ThemeApplier;
        $settings = $themeApplier->resolveReferences($settings);

        $mediaQueries = collect($blocks)
            ->map(fn (array $b) => $this->registry->find($b['type'] ?? '')?->getMediaQueries())
            ->filter()
            ->unique()
            ->join("\n");

        $blocksHtml = collect($blocks)
            ->map(function (array $block) {
                $instance = $this->registry->find($block['type'] ?? '');

                return $instance?->render($block['props'] ?? []) ?? '';
            })
            ->join("\n");

        $html = view('mail-editor::email-wrapper', [
            'content' => $blocksHtml,
            'settings' => $settings,
            'mediaQueries' => $mediaQueries,
        ])->render();

        return (new CssToInlineStyles)->convert($html);
    }

    /**
     * Export both HTML and plaintext versions.
     *
     * @return array{html: string, plaintext: string}
     */
    public function exportWithPlaintext(array $blocks, array $settings = []): array
    {
        return [
            'html' => $this->export($blocks, $settings),
            'plaintext' => (new PlaintextGenerator)->generate($blocks),
        ];
    }

    /** @return list<string> */
    public static function extractVariables(array $blocks): array
    {
        $text = collect($blocks)->map(fn (array $b) => json_encode($b['props'] ?? []))->join(' ');

        return VariableEngine::extractVariables($text);
    }

    /** @return list<string> */
    public function validate(array $blocks): array
    {
        $warnings = [];

        $hasFooter = collect($blocks)->contains(fn (array $b) => ($b['type'] ?? '') === 'footer');
        if (! $hasFooter) {
            $warnings[] = 'No Footer block found. Unsubscribe is required by law (CAN-SPAM/LGPD).';
        }

        $hasPreheader = collect($blocks)->contains(fn (array $b) => ($b['type'] ?? '') === 'preheader');
        if (! $hasPreheader) {
            $warnings[] = 'No Preheader block found. Recommended to improve open rates.';
        }

        collect($blocks)
            ->filter(fn (array $b) => ($b['type'] ?? '') === 'image')
            ->each(function (array $b) use (&$warnings) {
                if (empty($b['props']['alt'])) {
                    $warnings[] = 'Image without alt text. Required for accessibility.';
                }
            });

        $estimatedSize = strlen((string) json_encode($blocks)) * 1.5;
        if ($estimatedSize > 102400) {
            $warnings[] = 'Template may exceed 100KB. Some clients clip larger emails.';
        }

        return $warnings;
    }
}
