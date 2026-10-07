<?php

namespace JeffersonGoncalves\MailEditor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use JeffersonGoncalves\MailEditor\Enums\TemplateStatus;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class TemplateStatusChanged
{
    use Dispatchable;

    public function __construct(
        public readonly EmailTemplate $template,
        public readonly TemplateStatus $oldStatus,
        public readonly TemplateStatus $newStatus,
    ) {}
}
