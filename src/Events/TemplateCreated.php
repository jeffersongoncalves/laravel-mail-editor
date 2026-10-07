<?php

namespace JeffersonGoncalves\MailEditor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class TemplateCreated
{
    use Dispatchable;

    public function __construct(
        public readonly EmailTemplate $template,
    ) {}
}
