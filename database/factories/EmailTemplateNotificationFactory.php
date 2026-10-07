<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Enums\NotificationType;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateNotification;

/**
 * @extends Factory<EmailTemplateNotification>
 */
final class EmailTemplateNotificationFactory extends Factory
{
    protected $model = EmailTemplateNotification::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'template_id' => EmailTemplate::factory(),
            'type' => fake()->randomElement(NotificationType::cases()),
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => fake()->numberBetween(1, 1000),
            'message' => fake()->sentence(),
            'read_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes): array => [
            'read_at' => now(),
        ]);
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes): array => [
            'read_at' => null,
        ]);
    }
}
