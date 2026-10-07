<?php

namespace JeffersonGoncalves\MailEditor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVersion;

class VersionRestored
{
    use Dispatchable;

    public function __construct(
        public readonly EmailTemplate $template,
        public readonly EmailTemplateVersion $version,
    ) {}
}
