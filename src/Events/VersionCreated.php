<?php

namespace JeffersonGoncalves\MailEditor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVersion;

class VersionCreated
{
    use Dispatchable;

    public function __construct(
        public readonly EmailTemplateVersion $version,
    ) {}
}
