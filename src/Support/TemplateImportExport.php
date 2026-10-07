<?php

namespace JeffersonGoncalves\MailEditor\Support;

use Illuminate\Support\Str;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class TemplateImportExport
{
    public const FORMAT = 'mail-editor';

    /** Format id written by filament-mail-editor before the Laravel core was extracted; still importable. */
    public const LEGACY_FORMAT = 'filament-mail-editor';

    /**
     * Export a template as a portable JSON structure.
     *
     * @return array<string, mixed>
     */
    public function export(EmailTemplate $template): array
    {
        return [
            'format' => self::FORMAT,
            'version' => '1.0',
            'exported_at' => now()->toIso8601String(),
            'template' => [
                'name' => $template->name,
                'subject' => $template->subject,
                'preheader' => $template->preheader,
                'category' => $template->category,
                'blocks' => $template->blocks ?? [],
                'settings' => $template->settings ?? [],
            ],
        ];
    }

    /**
     * Export a template to a JSON string.
     */
    public function exportJson(EmailTemplate $template): string
    {
        return (string) json_encode($this->export($template), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Import a template from a JSON string.
     */
    public function importJson(string $json): EmailTemplate
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return $this->importArray($data);
    }

    /**
     * Import a template from an array structure.
     */
    public function importArray(array $data): EmailTemplate
    {
        $this->validateImportData($data);

        $templateData = $data['template'];

        $model = config('mail-editor.model', EmailTemplate::class);

        $baseName = $templateData['name'] ?? 'Imported Template';
        $name = $this->resolveUniqueName($model, $baseName);

        return $model::create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(5),
            'subject' => $templateData['subject'] ?? '',
            'preheader' => $templateData['preheader'] ?? null,
            'category' => $templateData['category'] ?? 'transactional',
            'blocks' => $this->sanitizeBlocks($templateData['blocks'] ?? []),
            'settings' => $templateData['settings'] ?? [],
            'is_active' => false,
        ]);
    }

    /**
     * Validate the import data structure.
     *
     * @throws \InvalidArgumentException
     */
    protected function validateImportData(array $data): void
    {
        if (! in_array($data['format'] ?? '', [self::FORMAT, self::LEGACY_FORMAT], true)) {
            throw new \InvalidArgumentException('Invalid import format. Expected "'.self::FORMAT.'".');
        }

        if (! isset($data['template'])) {
            throw new \InvalidArgumentException('Missing "template" key in import data.');
        }

        if (! isset($data['template']['blocks']) || ! is_array($data['template']['blocks'])) {
            throw new \InvalidArgumentException('Missing or invalid "blocks" in template data.');
        }
    }

    /**
     * Sanitize imported blocks: regenerate IDs and validate types.
     *
     * @param  list<array<string, mixed>>  $blocks
     * @return list<array<string, mixed>>
     */
    protected function sanitizeBlocks(array $blocks): array
    {
        $registry = app(BlockRegistry::class);

        return array_values(array_filter(array_map(function (array $block) use ($registry) {
            $type = $block['type'] ?? '';

            // Skip blocks with unknown types
            if (! $registry->find($type)) {
                return null;
            }

            // Regenerate block IDs to avoid collisions
            return [
                'id' => 'b_'.Str::random(7),
                'type' => $type,
                'props' => $block['props'] ?? [],
            ];
        }, $blocks)));
    }

    /**
     * Generate a unique name by appending (import N) if needed.
     *
     * @param  class-string  $model
     */
    protected function resolveUniqueName(string $model, string $baseName): string
    {
        if (! $model::where('name', $baseName)->exists()) {
            return $baseName;
        }

        $counter = 1;
        do {
            $name = $baseName.' (import '.$counter.')';
            $counter++;
        } while ($model::where('name', $name)->exists());

        return $name;
    }
}
