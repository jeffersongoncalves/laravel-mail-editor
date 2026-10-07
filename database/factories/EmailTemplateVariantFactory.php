<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVariant;

/**
 * @extends Factory<EmailTemplateVariant>
 */
final class EmailTemplateVariantFactory extends Factory
{
    protected $model = EmailTemplateVariant::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'template_id' => EmailTemplate::factory(),
            'name' => 'Variant '.fake()->randomLetter(),
            'blocks' => [
                [
                    'id' => (string) fake()->uuid(),
                    'type' => 'heading',
                    'props' => ['text' => fake()->sentence(3), 'level' => 'h1'],
                ],
            ],
            'settings' => null,
            'send_percentage' => 50,
            'is_winner' => false,
            'sends_count' => 0,
            'opens_count' => 0,
            'clicks_count' => 0,
        ];
    }

    public function winner(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_winner' => true,
        ]);
    }

    public function withStats(): static
    {
        return $this->state(function (array $attributes): array {
            $sends = fake()->numberBetween(100, 10000);
            $opens = fake()->numberBetween(0, $sends);
            $clicks = fake()->numberBetween(0, $opens);

            return [
                'sends_count' => $sends,
                'opens_count' => $opens,
                'clicks_count' => $clicks,
            ];
        });
    }
}
