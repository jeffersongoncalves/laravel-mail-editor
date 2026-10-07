<?php

use JeffersonGoncalves\MailEditor\Support\VariableEngine;

beforeEach(function () {
    $this->engine = new VariableEngine;
});

it('replaces simple variables', function () {
    $result = $this->engine->process('Hello {{name}}!', ['name' => 'John']);

    expect($result)->toBe('Hello John!');
});

it('supports fallback values', function () {
    $result = $this->engine->process('Hello {{name|Visitor}}!', []);

    expect($result)->toBe('Hello Visitor!');
});

it('uses variable value over fallback', function () {
    $result = $this->engine->process('Hello {{name|Visitor}}!', ['name' => 'John']);

    expect($result)->toBe('Hello John!');
});

it('leaves unresolved variables without fallback', function () {
    $result = $this->engine->process('Hello {{name}}!', []);

    expect($result)->toBe('Hello {{name}}!');
});

it('processes if conditionals - truthy', function () {
    $result = $this->engine->process('{{#if premium}}VIP Content{{/if}}', ['premium' => true]);

    expect($result)->toBe('VIP Content');
});

it('processes if conditionals - falsy', function () {
    $result = $this->engine->process('{{#if premium}}VIP Content{{/if}}', ['premium' => false]);

    expect($result)->toBe('');
});

it('processes if/else conditionals', function () {
    $result = $this->engine->process('{{#if premium}}VIP{{#else}}Free{{/if}}', ['premium' => false]);

    expect($result)->toBe('Free');
});

it('processes each loops with objects', function () {
    $result = $this->engine->process(
        '{{#each products}}{{this.name}}: ${{this.price}} {{/each}}',
        ['products' => [
            ['name' => 'Widget', 'price' => '9.99'],
            ['name' => 'Gadget', 'price' => '19.99'],
        ]]
    );

    expect($result)->toBe('Widget: $9.99 Gadget: $19.99 ');
});

it('processes each loops with scalars', function () {
    $result = $this->engine->process(
        '{{#each items}}{{this}}, {{/each}}',
        ['items' => ['apple', 'banana', 'cherry']]
    );

    expect($result)->toBe('apple, banana, cherry, ');
});

it('provides @index in loops', function () {
    $result = $this->engine->process(
        '{{#each items}}{{@index}}-{{this}} {{/each}}',
        ['items' => ['a', 'b', 'c']]
    );

    expect($result)->toBe('0-a 1-b 2-c ');
});

it('handles empty loop variable gracefully', function () {
    $result = $this->engine->process('{{#each items}}{{this}}{{/each}}', ['items' => []]);

    expect($result)->toBe('');
});

it('processes props recursively', function () {
    $props = [
        'text' => 'Hello {{name|User}}',
        'nested' => [
            'url' => 'https://example.com/{{slug}}',
        ],
    ];

    $result = $this->engine->processProps($props, ['name' => 'John', 'slug' => 'welcome']);

    expect($result['text'])->toBe('Hello John');
    expect($result['nested']['url'])->toBe('https://example.com/welcome');
});

it('extracts all variable names from text', function () {
    $text = '{{name}} {{#if premium}}{{plan}}{{/if}} {{#each items}}{{this}}{{/each}} {{email|default}}';

    $vars = VariableEngine::extractVariables($text);

    expect($vars)->toContain('name', 'premium', 'plan', 'items', 'email');
});
