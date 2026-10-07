<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Models\EmailTheme;

/**
 * @extends Factory<EmailTheme>
 */
final class EmailThemeFactory extends Factory
{
    protected $model = EmailTheme::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug()->toString().'-'.fake()->unique()->numberBetween(1, 99999),
            'is_default' => false,
            'is_system' => false,
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
                'font_size_base' => 14,
                'line_height_base' => 1.7,
                'border_radius' => 4,
            ],
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_default' => true,
        ]);
    }

    public function system(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_system' => true,
        ]);
    }
}
