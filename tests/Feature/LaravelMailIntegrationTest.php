<?php

use Illuminate\Support\Facades\Mail;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Support\TemplateMailableBridge;

it('creates a mailable bridge from slug', function () {
    EmailTemplate::create([
        'name' => 'Welcome',
        'slug' => 'welcome',
        'subject' => 'Welcome {{nome}}',
        'blocks' => [
            ['id' => 'b_1', 'type' => 'heading', 'props' => ['text' => 'Hello {{nome}}']],
            ['id' => 'b_2', 'type' => 'paragraph', 'props' => ['html' => 'Welcome to {{empresa}}']],
        ],
    ]);

    $mailable = new TemplateMailableBridge('welcome', ['nome' => 'Jefferson', 'empresa' => 'Acme']);

    expect($mailable)->toBeInstanceOf(TemplateMailableBridge::class);
});

it('has Mail::template macro available', function () {
    expect(Mail::hasMacro('template'))->toBeTrue();
});
