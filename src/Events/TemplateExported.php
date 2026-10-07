<?php

namespace JeffersonGoncalves\MailEditor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class TemplateExported
{
    use Dispatchable;

    public function __construct(
        public readonly EmailTemplate $template,
        public readonly string $format,
    ) {}
}
