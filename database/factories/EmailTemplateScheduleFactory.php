<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Enums\ScheduleStatus;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateSchedule;

/**
 * @extends Factory<EmailTemplateSchedule>
 */
final class EmailTemplateScheduleFactory extends Factory
{
    protected $model = EmailTemplateSchedule::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'template_id' => EmailTemplate::factory(),
            'status' => ScheduleStatus::Pending,
            'scheduled_at' => now()->addHours(fake()->numberBetween(1, 72)),
            'sent_at' => null,
            'recipients_type' => 'list',
            'recipients' => [fake()->safeEmail(), fake()->safeEmail()],
            'variables' => null,
            'variant_id' => null,
            'scheduled_by' => fake()->name(),
            'cancelled_by' => null,
            'cancelled_at' => null,
            'cancel_reason' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScheduleStatus::Pending,
        ]);
    }

    public function due(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScheduleStatus::Pending,
            'scheduled_at' => now()->subMinutes(5),
        ]);
    }

    public function sent(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScheduleStatus::Sent,
            'scheduled_at' => now()->subHour(),
            'sent_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScheduleStatus::Failed,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ScheduleStatus::Cancelled,
            'cancelled_by' => fake()->name(),
            'cancelled_at' => now(),
            'cancel_reason' => fake()->sentence(),
        ]);
    }
}
