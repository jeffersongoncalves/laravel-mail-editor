<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVariant;

it('creates a variant for a template', function () {
    $template = EmailTemplate::create([
        'name' => 'AB Original',
        'slug' => 'ab-original',
        'subject' => 'Original Subject',
        'blocks' => [['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Version A']]],
    ]);

    $variant = EmailTemplateVariant::create([
        'template_id' => $template->id,
        'name' => 'B',
        'blocks' => [['id' => 'b_2', 'type' => 'heading', 'props' => ['text' => 'Version B']]],
        'send_percentage' => 50,
    ]);

    expect($variant->template->id)->toBe($template->id);
    expect($template->variants()->count())->toBe(1);
});

it('variant belongs to template', function () {
    $template = EmailTemplate::create([
        'name' => 'AB Relation',
        'slug' => 'ab-relation',
        'subject' => 'Test',
        'blocks' => [],
    ]);

    $variant = EmailTemplateVariant::create([
        'template_id' => $template->id,
        'name' => 'B',
        'blocks' => [['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'B']]],
        'send_percentage' => 50,
    ]);

    expect($variant->template)->toBeInstanceOf(EmailTemplate::class);
    expect($variant->template->id)->toBe($template->id);
    expect($variant->blocks)->toBeArray();
    expect($variant->blocks[0]['props']['text'])->toBe('B');
});
