<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Seeders;

use Illuminate\Database\Seeder;

final class MailEditorSeeder extends Seeder
{
    /**
     * Orchestrator seeder. Run via:
     *   php artisan db:seed --class="JeffersonGoncalves\\MailEditor\\Database\\Seeders\\MailEditorSeeder"
     */
    public function run(): void
    {
        $this->call([
            EmailTemplateCategorySeeder::class,
            EmailThemeSeeder::class,
            EmailBrandKitSeeder::class,
            EmailTemplateSeeder::class,
        ]);
    }
}
