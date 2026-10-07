<?php

namespace JeffersonGoncalves\MailEditor\Support;

use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVariant;

class VariantDistributor
{
    /**
     * Select a template or variant deterministically based on userId.
     *
     * Uses crc32 hash for consistent assignment — the same user always
     * sees the same variant for a given template. Percentages are accumulated
     * to form ranges (e.g., A=30%, B=70% becomes A=[0,30), B=[30,100)).
     */
    public function select(string $slug, string $userId): EmailTemplate|EmailTemplateVariant
    {
        $model = config('mail-editor.model', EmailTemplate::class);
        $template = $model::where('slug', $slug)->firstOrFail();

        $variants = $template->variants()->orderBy('id')->get();

        if ($variants->isEmpty()) {
            return $template;
        }

        $hash = abs(crc32($userId.$slug)) % 100;
        $cumulative = 0;

        foreach ($variants as $variant) {
            $cumulative += $variant->send_percentage;

            if ($hash < $cumulative) {
                return $variant;
            }
        }

        return $template;
    }

    /**
     * Validate that variant percentages sum to 100 or less.
     */
    public function validatePercentages(EmailTemplate $template): bool
    {
        $total = $template->variants()->sum('send_percentage');

        return $total <= 100;
    }
}
