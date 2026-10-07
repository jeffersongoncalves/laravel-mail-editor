<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Seeders;

use Illuminate\Database\Seeder;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateCategory;

final class EmailTemplateCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Marketing', 'slug' => 'marketing', 'color' => '#10b981', 'icon' => 'heroicon-o-megaphone', 'sort_order' => 0],
            ['name' => 'Transactional', 'slug' => 'transactional', 'color' => '#3b82f6', 'icon' => 'heroicon-o-paper-airplane', 'sort_order' => 1],
            ['name' => 'Notifications', 'slug' => 'notifications', 'color' => '#f59e0b', 'icon' => 'heroicon-o-bell', 'sort_order' => 2],
            ['name' => 'Newsletters', 'slug' => 'newsletters', 'color' => '#8b5cf6', 'icon' => 'heroicon-o-envelope', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            EmailTemplateCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
