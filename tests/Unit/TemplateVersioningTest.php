<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVersion;

beforeEach(function () {
    $this->template = EmailTemplate::create([
        'name' => 'Test Template',
        'slug' => 'test-template',
        'subject' => 'Test Subject',
        'blocks' => [['id' => 'b_1', 'type' => 'paragraph', 'props' => ['html' => 'v1']]],
        'settings' => ['primary_color' => '#000'],
    ]);
});

it('creates a version snapshot', function () {
    $version = $this->template->createVersion('Initial save');

    expect($version)->toBeInstanceOf(EmailTemplateVersion::class);
    expect($version->version_number)->toBe(1);
    expect($version->reason)->toBe('Initial save');
    expect($version->blocks)->toBe($this->template->blocks);
    expect($version->subject)->toBe('Test Subject');
});

it('increments version numbers', function () {
    $this->template->createVersion('v1');
    $this->template->createVersion('v2');
    $v3 = $this->template->createVersion('v3');

    expect($v3->version_number)->toBe(3);
    expect($this->template->versions()->count())->toBe(3);
});

it('restores to a specific version', function () {
    $this->template->createVersion('original');

    $this->template->update([
        'blocks' => [['id' => 'b_2', 'type' => 'heading', 'props' => ['text' => 'changed']]],
        'subject' => 'Changed Subject',
    ]);

    $version = $this->template->versions()->first();
    $this->template->restoreVersion($version->id);

    $this->template->refresh();
    expect($this->template->blocks[0]['props']['html'])->toBe('v1');
    expect($this->template->subject)->toBe('Test Subject');
});

it('has versions relationship', function () {
    $this->template->createVersion('test');

    expect($this->template->versions)->toHaveCount(1);
    expect($this->template->versions->first()->template_id)->toBe($this->template->id);
});

it('stores created_by', function () {
    $version = $this->template->createVersion('save', 'admin@test.com');

    expect($version->created_by)->toBe('admin@test.com');
});
