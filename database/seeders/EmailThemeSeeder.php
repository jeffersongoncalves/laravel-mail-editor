<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Seeders;

use Illuminate\Database\Seeder;
use JeffersonGoncalves\MailEditor\Models\EmailTheme;

final class EmailThemeSeeder extends Seeder
{
    public function run(): void
    {
        $themes = [
            [
                'slug' => 'default',
                'name' => 'Default',
                'is_default' => true,
                'colors' => [
                    'primary_color' => '#378ADD',
                    'secondary_color' => '#1A3A5C',
                    'accent_color' => '#EF9F27',
                    'bg_color' => '#f8f9fa',
                    'content_bg' => '#ffffff',
                    'text_color' => '#333333',
                    'muted_color' => '#666666',
                    'button_bg' => '#378ADD',
                    'button_text' => '#ffffff',
                ],
                'typography' => [
                    'font_family' => 'Arial, Helvetica, sans-serif',
                    'font_size_base' => 14,
                    'line_height_base' => 1.7,
                    'border_radius' => 4,
                ],
            ],
            [
                'slug' => 'dark',
                'name' => 'Dark',
                'is_default' => false,
                'colors' => [
                    'primary_color' => '#60A5FA',
                    'secondary_color' => '#1E3A5F',
                    'accent_color' => '#FBBF24',
                    'bg_color' => '#1a1a2e',
                    'content_bg' => '#16213e',
                    'text_color' => '#e0e0e0',
                    'muted_color' => '#9ca3af',
                    'button_bg' => '#60A5FA',
                    'button_text' => '#000000',
                ],
                'typography' => [
                    'font_family' => 'Arial, Helvetica, sans-serif',
                    'font_size_base' => 14,
                    'line_height_base' => 1.7,
                    'border_radius' => 6,
                ],
            ],
            [
                'slug' => 'minimal',
                'name' => 'Minimal',
                'is_default' => false,
                'colors' => [
                    'primary_color' => '#111111',
                    'secondary_color' => '#333333',
                    'accent_color' => '#666666',
                    'bg_color' => '#ffffff',
                    'content_bg' => '#ffffff',
                    'text_color' => '#111111',
                    'muted_color' => '#888888',
                    'button_bg' => '#111111',
                    'button_text' => '#ffffff',
                ],
                'typography' => [
                    'font_family' => 'Georgia, serif',
                    'font_size_base' => 16,
                    'line_height_base' => 1.8,
                    'border_radius' => 0,
                ],
            ],
            [
                'slug' => 'vibrant',
                'name' => 'Vibrant',
                'is_default' => false,
                'colors' => [
                    'primary_color' => '#7C3AED',
                    'secondary_color' => '#4F46E5',
                    'accent_color' => '#F59E0B',
                    'bg_color' => '#FAF5FF',
                    'content_bg' => '#ffffff',
                    'text_color' => '#1F2937',
                    'muted_color' => '#6B7280',
                    'button_bg' => '#7C3AED',
                    'button_text' => '#ffffff',
                ],
                'typography' => [
                    'font_family' => 'Verdana, sans-serif',
                    'font_size_base' => 14,
                    'line_height_base' => 1.6,
                    'border_radius' => 8,
                ],
            ],
        ];

        foreach ($themes as $theme) {
            EmailTheme::firstOrCreate(
                ['slug' => $theme['slug']],
                array_merge($theme, ['is_system' => true]),
            );
        }
    }
}
