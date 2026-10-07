<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Support\TemplateImportExport;

beforeEach(function () {
    $this->importer = new TemplateImportExport;

    $this->template = EmailTemplate::create([
        'name' => 'Export Test',
        'slug' => 'export-test',
        'subject' => 'Subject',
        'preheader' => 'Preview text',
        'category' => 'marketing',
        'blocks' => [
            ['id' => 'b_1', 'type' => 'paragraph', 'props' => ['html' => '<p>Hello</p>']],
            ['id' => 'b_2', 'type' => 'button', 'props' => ['text' => 'Click', 'url' => 'https://example.com']],
        ],
        'settings' => ['primary_color' => '#FF0000'],
    ]);
});

it('exports a template as array', function () {
    $data = $this->importer->export($this->template);

    expect($data['format'])->toBe('mail-editor');
    expect($data['version'])->toBe('1.0');
    expect($data['template']['name'])->toBe('Export Test');
    expect($data['template']['blocks'])->toHaveCount(2);
    expect($data['template']['settings']['primary_color'])->toBe('#FF0000');
});

it('exports and imports roundtrip', function () {
    $json = $this->importer->exportJson($this->template);
    $imported = $this->importer->importJson($json);

    expect($imported)->toBeInstanceOf(EmailTemplate::class);
    expect($imported->subject)->toBe('Subject');
    expect($imported->blocks)->toHaveCount(2);
    expect($imported->is_active)->toBeFalse();
});

it('generates unique name on import conflict', function () {
    $json = $this->importer->exportJson($this->template);

    $first = $this->importer->importJson($json);
    $second = $this->importer->importJson($json);

    expect($first->name)->toBe('Export Test (import 1)');
    expect($second->name)->toBe('Export Test (import 2)');
});

it('regenerates block IDs on import', function () {
    $json = $this->importer->exportJson($this->template);
    $imported = $this->importer->importJson($json);

    $originalIds = array_column($this->template->blocks, 'id');
    $importedIds = array_column($imported->blocks, 'id');

    expect($importedIds)->not->toBe($originalIds);
});

it('still imports JSON exported with the legacy filament-mail-editor format id', function () {
    $legacy = json_encode(['format' => 'filament-mail-editor', 'template' => ['name' => 'Old', 'blocks' => []]]);

    expect($this->importer->importJson($legacy)->name)->toBe('Old');
});

it('rejects invalid format', function () {
    $this->importer->importJson('{"format":"other","template":{"blocks":[]}}');
})->throws(InvalidArgumentException::class, 'Invalid import format');

it('rejects missing template key', function () {
    $this->importer->importJson('{"format":"mail-editor"}');
})->throws(InvalidArgumentException::class, 'Missing "template" key');

it('exports valid JSON string', function () {
    $json = $this->importer->exportJson($this->template);
    $decoded = json_decode($json, true);

    expect($decoded)->toBeArray();
    expect($decoded['format'])->toBe('mail-editor');
});
