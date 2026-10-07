<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Models\EmailBrandKit;

/**
 * @extends Factory<EmailBrandKit>
 */
final class EmailBrandKitFactory extends Factory
{
    protected $model = EmailBrandKit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString().'-'.fake()->unique()->numberBetween(1, 99999),
            'is_default' => false,
            'logo_url' => fake()->imageUrl(400, 120, 'abstract'),
            'logo_alt' => $name.' logo',
            'colors' => [
                'primary_color' => fake()->hexColor(),
                'secondary_color' => fake()->hexColor(),
                'accent_color' => fake()->hexColor(),
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
            'social_links' => [
                ['platform' => 'twitter', 'url' => 'https://twitter.com/'.fake()->userName()],
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/'.fake()->slug(2)],
            ],
            'footer_address' => fake()->address(),
            'unsubscribe_url' => 'https://example.com/unsubscribe',
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_default' => true,
        ]);
    }
}
