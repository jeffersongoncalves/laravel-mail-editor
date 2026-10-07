<?php

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateCategory;

beforeEach(function () {
    $this->artisan('migrate', ['--database' => 'testing']);
});

it('creates a category', function () {
    $category = EmailTemplateCategory::create([
        'name' => 'Marketing',
        'slug' => 'marketing',
        'color' => 'green',
    ]);

    expect($category->name)->toBe('Marketing');
    expect($category->slug)->toBe('marketing');
});

it('supports parent-child hierarchy', function () {
    $parent = EmailTemplateCategory::create([
        'name' => 'Marketing',
        'slug' => 'marketing',
    ]);

    $child = EmailTemplateCategory::create([
        'name' => 'Newsletters',
        'slug' => 'newsletters',
        'parent_id' => $parent->id,
    ]);

    expect($child->parent->name)->toBe('Marketing');
    expect($parent->children)->toHaveCount(1);
    expect($parent->children->first()->name)->toBe('Newsletters');
});

it('generates full path', function () {
    $parent = EmailTemplateCategory::create([
        'name' => 'Marketing',
        'slug' => 'marketing',
    ]);

    $child = EmailTemplateCategory::create([
        'name' => 'Newsletters',
        'slug' => 'newsletters',
        'parent_id' => $parent->id,
    ]);

    expect($child->getFullPath())->toBe('Marketing > Newsletters');
});

it('has templates relationship', function () {
    $category = EmailTemplateCategory::create([
        'name' => 'Transactional',
        'slug' => 'transactional',
    ]);

    EmailTemplate::create([
        'name' => 'Welcome',
        'slug' => 'welcome',
        'subject' => 'Welcome!',
        'blocks' => [],
        'category_id' => $category->id,
    ]);

    expect($category->templates)->toHaveCount(1);
});

it('supports sort order', function () {
    EmailTemplateCategory::create(['name' => 'B', 'slug' => 'b', 'sort_order' => 2]);
    EmailTemplateCategory::create(['name' => 'A', 'slug' => 'a', 'sort_order' => 1]);

    $categories = EmailTemplateCategory::orderBy('sort_order')->get();

    expect($categories->first()->name)->toBe('A');
});
