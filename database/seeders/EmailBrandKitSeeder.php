<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Seeders;

use Illuminate\Database\Seeder;
use JeffersonGoncalves\MailEditor\Models\EmailBrandKit;

final class EmailBrandKitSeeder extends Seeder
{
    public function run(): void
    {
        EmailBrandKit::firstOrCreate(
            ['slug' => 'default-brand-kit'],
            [
                'name' => 'Default Brand Kit',
                'is_default' => true,
                'logo_url' => null,
                'logo_alt' => 'Your Company',
                'colors' => [
                    'primary_color' => '#3b82f6',
                    'secondary_color' => '#1e40af',
                    'accent_color' => '#f59e0b',
                    'bg_color' => '#f9fafb',
                    'content_bg' => '#ffffff',
                    'text_color' => '#111827',
                    'muted_color' => '#6b7280',
                    'button_bg' => '@primary_color',
                    'button_text' => '#ffffff',
                ],
                'typography' => [
                    'font_family' => 'Arial, sans-serif',
                    'font_size_base' => 16,
                    'line_height_base' => 1.5,
                    'border_radius' => 6,
                ],
                'social_links' => [],
                'footer_address' => '123 Main St, Your City',
                'unsubscribe_url' => 'https://example.com/unsubscribe',
            ],
        );
    }
}
