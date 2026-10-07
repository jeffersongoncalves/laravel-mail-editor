<?php

namespace JeffersonGoncalves\MailEditor\Commands;

use Illuminate\Console\Command;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class ReleaseLocksCommand extends Command
{
    protected $signature = 'mail-editor:release-locks';

    protected $description = 'Release expired editing locks on email templates';

    public function handle(): int
    {
        $model = config('mail-editor.model', EmailTemplate::class);

        $released = $model::query()
            ->whereNotNull('locked_by')
            ->whereNotNull('lock_expires_at')
            ->where('lock_expires_at', '<', now())
            ->update([
                'locked_by' => null,
                'locked_at' => null,
                'lock_expires_at' => null,
            ]);

        $this->info("Released {$released} expired lock(s).");

        return self::SUCCESS;
    }
}
