<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVariant;
use JeffersonGoncalves\MailEditor\Support\TemplateVariantSelector;

it('returns template when no variants exist', function () {
    $template = EmailTemplate::create([
        'name' => 'AB Test',
        'slug' => 'ab-test',
        'subject' => 'Test',
        'blocks' => [],
    ]);

    $selector = new TemplateVariantSelector;
    $result = $selector->select('ab-test', 'user-123');

    expect($result)->toBeInstanceOf(EmailTemplate::class);
    expect($result->id)->toBe($template->id);
});

it('returns variant deterministically for same user', function () {
    $template = EmailTemplate::create([
        'name' => 'AB Test 2',
        'slug' => 'ab-test-2',
        'subject' => 'Test',
        'blocks' => [['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'A']]],
    ]);

    EmailTemplateVariant::create([
        'template_id' => $template->id,
        'name' => 'B',
        'blocks' => [['id' => 'b_2', 'type' => 'heading', 'props' => ['text' => 'B']]],
        'send_percentage' => 50,
    ]);

    $selector = new TemplateVariantSelector;

    $first = $selector->select('ab-test-2', 'user-456');
    $second = $selector->select('ab-test-2', 'user-456');

    // Same user always gets same result
    expect(get_class($first))->toBe(get_class($second));
});

it('distributes users between template and variant', function () {
    $template = EmailTemplate::create([
        'name' => 'AB Test 3',
        'slug' => 'ab-test-3',
        'subject' => 'Test',
        'blocks' => [],
    ]);

    EmailTemplateVariant::create([
        'template_id' => $template->id,
        'name' => 'B',
        'blocks' => [],
        'send_percentage' => 50,
    ]);

    $selector = new TemplateVariantSelector;
    $templateCount = 0;
    $variantCount = 0;

    for ($i = 0; $i < 100; $i++) {
        $result = $selector->select('ab-test-3', "user-{$i}");
        if ($result instanceof EmailTemplateVariant) {
            $variantCount++;
        } else {
            $templateCount++;
        }
    }

    // Both should get some traffic (not all one way)
    expect($variantCount)->toBeGreaterThan(0);
    expect($templateCount)->toBeGreaterThan(0);
});
