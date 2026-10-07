<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateVersion;

/**
 * @extends Factory<EmailTemplateVersion>
 */
final class EmailTemplateVersionFactory extends Factory
{
    protected $model = EmailTemplateVersion::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'template_id' => EmailTemplate::factory(),
            'version_number' => fake()->numberBetween(1, 99),
            'blocks' => [
                [
                    'id' => (string) fake()->uuid(),
                    'type' => 'paragraph',
                    'props' => ['text' => fake()->paragraph()],
                ],
            ],
            'settings' => ['primary_color' => '#3b82f6'],
            'subject' => fake()->sentence(5),
            'preheader' => fake()->optional()->sentence(),
            'reason' => fake()->optional()->sentence(),
            'created_by' => fake()->name(),
            'created_at' => now(),
        ];
    }
}
