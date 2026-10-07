<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Enums\BlockCategory;
use JeffersonGoncalves\MailEditor\Models\SavedEmailBlock;

/**
 * @extends Factory<SavedEmailBlock>
 */
final class SavedEmailBlockFactory extends Factory
{
    protected $model = SavedEmailBlock::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['heading', 'paragraph', 'button', 'hero', 'footer']);

        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->optional()->sentence(),
            'thumbnail' => null,
            'type' => $type,
            'props' => $this->propsForType($type),
            'user_id' => null,
            'is_global' => false,
            'category' => fake()->randomElement(BlockCategory::cases()),
        ];
    }

    public function global(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_global' => true,
            'user_id' => null,
        ]);
    }

    public function forUser(int $userId): static
    {
        return $this->state(fn (array $attributes): array => [
            'user_id' => $userId,
            'is_global' => false,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function propsForType(string $type): array
    {
        return match ($type) {
            'heading' => ['text' => fake()->sentence(3), 'level' => 'h1'],
            'paragraph' => ['text' => fake()->paragraph()],
            'button' => ['text' => 'Click me', 'url' => 'https://example.com'],
            'hero' => ['title' => fake()->sentence(4), 'subtitle' => fake()->sentence()],
            'footer' => ['address' => fake()->address()],
            default => [],
        };
    }
}
