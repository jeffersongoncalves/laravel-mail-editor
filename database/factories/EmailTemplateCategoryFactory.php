<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateCategory;

/**
 * @extends Factory<EmailTemplateCategory>
 */
final class EmailTemplateCategoryFactory extends Factory
{
    protected $model = EmailTemplateCategory::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => str($name)->slug()->toString().'-'.fake()->unique()->numberBetween(1, 99999),
            'color' => fake()->hexColor(),
            'icon' => fake()->randomElement(['heroicon-o-folder', 'heroicon-o-envelope', 'heroicon-o-megaphone']),
            'description' => fake()->optional()->sentence(),
            'parent_id' => null,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function withParent(?EmailTemplateCategory $parent = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'parent_id' => $parent?->id ?? EmailTemplateCategory::factory(),
        ]);
    }
}
