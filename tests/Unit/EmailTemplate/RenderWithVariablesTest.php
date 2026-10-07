<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Support\HtmlExporter;

it('replaces variables in rendered HTML', function () {
    $template = EmailTemplate::create([
        'name' => 'Variable Test',
        'slug' => 'variable-test',
        'subject' => 'Hello {{nome}}',
        'blocks' => [
            ['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Welcome, {{nome}}!']],
            ['id' => 'b_2', 'type' => 'paragraph', 'props' => ['html' => 'Your company: {{empresa}}']],
        ],
        'settings' => [],
    ]);

    $html = $template->render(['nome' => 'Jefferson', 'empresa' => 'Acme Corp']);

    expect($html)
        ->toContain('Welcome, Jefferson!')
        ->toContain('Your company: Acme Corp')
        ->not->toContain('{{nome}}')
        ->not->toContain('{{empresa}}');
});

it('preserves unmatched variables', function () {
    $template = EmailTemplate::create([
        'name' => 'Partial Variables',
        'slug' => 'partial-variables',
        'subject' => 'Test',
        'blocks' => [
            ['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Hello {{nome}}, your code is {{codigo}}']],
        ],
        'settings' => [],
    ]);

    $html = $template->render(['nome' => 'Test User']);

    expect($html)
        ->toContain('Hello Test User')
        ->toContain('{{codigo}}');
});

it('extracts variables from blocks', function () {
    $vars = HtmlExporter::extractVariables([
        ['type' => 'heading', 'props' => ['text' => 'Hello {{nome}}']],
        ['type' => 'paragraph', 'props' => ['html' => 'Company: {{empresa}}, email: {{email}}']],
        ['type' => 'button', 'props' => ['text' => 'Click', 'url' => 'https://example.com/{{token}}']],
    ]);

    expect($vars)
        ->toContain('nome')
        ->toContain('empresa')
        ->toContain('email')
        ->toContain('token')
        ->toHaveCount(4);
});
