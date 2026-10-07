<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Enums\ActivityAction;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateActivity;

/**
 * @extends Factory<EmailTemplateActivity>
 */
final class EmailTemplateActivityFactory extends Factory
{
    protected $model = EmailTemplateActivity::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'template_id' => EmailTemplate::factory(),
            'action' => fake()->randomElement(ActivityAction::cases()),
            'performed_by' => fake()->name(),
            'metadata' => null,
            'created_at' => now(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes): array => [
            'action' => ActivityAction::Approved,
        ]);
    }

    public function versionCreated(): static
    {
        return $this->state(fn (array $attributes): array => [
            'action' => ActivityAction::VersionCreated,
        ]);
    }
}
