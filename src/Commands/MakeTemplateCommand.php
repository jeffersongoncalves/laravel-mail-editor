<?php

namespace JeffersonGoncalves\MailEditor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

class MakeTemplateCommand extends Command
{
    protected $signature = 'mail-editor:make-template {name} {--category=transactional : Template category (transactional, marketing, notification)}';

    protected $description = 'Create a new email template with default blocks';

    public function handle(): int
    {
        $name = $this->argument('name');
        $category = $this->option('category');
        $slug = Str::slug($name);

        $model = config('mail-editor.model', EmailTemplate::class);

        if ($model::where('slug', $slug)->exists()) {
            $this->error("Template with slug '{$slug}' already exists.");

            return self::FAILURE;
        }

        $blocks = $this->getDefaultBlocks($name, $category);

        $template = $model::create([
            'name' => $name,
            'slug' => $slug,
            'subject' => $name,
            'preheader' => '',
            'category' => $category,
            'blocks' => $blocks,
            'settings' => config('mail-editor.default_settings', []),
            'is_active' => true,
        ]);

        $this->info("Template '{$name}' created successfully (ID: {$template->id}).");

        return self::SUCCESS;
    }

    /** @return list<array{id: string, type: string, props: array<string, mixed>}> */
    protected function getDefaultBlocks(string $name, string $category): array
    {
        $blocks = [
            [
                'id' => 'b_'.Str::random(7),
                'type' => 'preheader',
                'props' => ['text' => ''],
            ],
            [
                'id' => 'b_'.Str::random(7),
                'type' => 'header',
                'props' => ['logo_src' => '', 'logo_alt' => $name, 'bg_color' => '#1A3A5C', 'align' => 'center'],
            ],
            [
                'id' => 'b_'.Str::random(7),
                'type' => 'hero',
                'props' => [
                    'title' => $name,
                    'subtitle' => '',
                    'bg_color' => '#185FA5',
                    'cta_text' => $category === 'marketing' ? 'Learn More' : '',
                    'cta_url' => '',
                    'align' => 'center',
                ],
            ],
            [
                'id' => 'b_'.Str::random(7),
                'type' => 'paragraph',
                'props' => ['html' => '<p>Your content here...</p>', 'color' => '#555555', 'font_size' => 14],
            ],
            [
                'id' => 'b_'.Str::random(7),
                'type' => 'footer',
                'props' => [
                    'address' => '{{company_address}}',
                    'unsubscribe_url' => '{{unsubscribe_url}}',
                    'unsubscribe_text' => 'Unsubscribe',
                    'copyright' => '© '.date('Y').' {{company_name}}',
                    'bg_color' => '#f8f9fa',
                    'text_color' => '#999999',
                    'font_size' => 11,
                ],
            ],
        ];

        return $blocks;
    }
}
