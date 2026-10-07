<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Enums\TemplateCategory;
use JeffersonGoncalves\MailEditor\Enums\TemplateStatus;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

/**
 * @extends Factory<EmailTemplate>
 */
final class EmailTemplateFactory extends Factory
{
    protected $model = EmailTemplate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString().'-'.fake()->unique()->numberBetween(1, 99999),
            'subject' => fake()->sentence(5),
            'preheader' => fake()->optional()->sentence(),
            'blocks' => [
                [
                    'id' => (string) fake()->uuid(),
                    'type' => 'heading',
                    'props' => ['text' => fake()->sentence(4), 'level' => 'h1'],
                ],
                [
                    'id' => (string) fake()->uuid(),
                    'type' => 'paragraph',
                    'props' => ['text' => fake()->paragraph()],
                ],
            ],
            'settings' => [
                'primary_color' => '#3b82f6',
                'bg_color' => '#f9fafb',
                'content_bg' => '#ffffff',
            ],
            'category' => fake()->randomElement(TemplateCategory::cases()),
            'category_id' => null,
            'is_active' => true,
            'status' => TemplateStatus::Draft,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TemplateStatus::Draft,
        ]);
    }

    public function review(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TemplateStatus::Review,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TemplateStatus::Approved,
            'approved_by' => fake()->name(),
            'approved_at' => now(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }

    public function locked(?string $lockedBy = null): static
    {
        return $this->state(fn (array $attributes): array => [
            'locked_by' => $lockedBy ?? fake()->name(),
            'locked_at' => now(),
            'lock_expires_at' => now()->addMinutes(30),
        ]);
    }

    public function marketing(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => TemplateCategory::Marketing,
        ]);
    }

    public function transactional(): static
    {
        return $this->state(fn (array $attributes): array => [
            'category' => TemplateCategory::Transactional,
        ]);
    }
}
