<?php

namespace JeffersonGoncalves\MailEditor\Support;

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVariant;

class TemplateVariantSelector
{
    /**
     * Select a template or variant deterministically based on userId.
     *
     * Uses crc32 hash for consistent assignment — the same user always
     * sees the same variant for a given template.
     */
    public function select(string $slug, string $userId): EmailTemplate|EmailTemplateVariant
    {
        $model = config('mail-editor.model', EmailTemplate::class);
        $template = $model::where('slug', $slug)->firstOrFail();

        if (! $template->variants()->exists()) {
            return $template;
        }

        $hash = abs(crc32($userId.$slug)) % 100;

        $variant = $template->variants()
            ->orderBy('send_percentage', 'asc')
            ->get()
            ->first(function (EmailTemplateVariant $v) use ($hash) {
                return $hash < $v->send_percentage;
            });

        return $variant ?? $template;
    }
}
