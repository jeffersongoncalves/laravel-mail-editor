<?php

declare(strict_types=1);

namespace JeffersonGoncalves\MailEditor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use JeffersonGoncalves\MailEditor\Enums\TemplateCategory;
use JeffersonGoncalves\MailEditor\Enums\TemplateStatus;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;
use JeffersonGoncalves\MailEditor\Models\EmailTemplateCategory as CategoryModel;

final class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome-email',
                'subject' => 'Welcome to {{company_name}}!',
                'preheader' => 'Get started with your new account',
                'category' => TemplateCategory::Transactional,
                'category_slug' => 'transactional',
                'blocks' => [
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'header',
                        'props' => [
                            'logo_src' => 'https://placehold.co/200x50/1A3A5C/FFFFFF?text=LOGO',
                            'logo_alt' => '{{company_name}}',
                            'bg_color' => '#1A3A5C',
                            'align' => 'center',
                            'padding' => '20px 24px',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Welcome, {{user_name}}!',
                            'level' => 'h1',
                            'color' => '#1a1a1a',
                            'align' => 'center',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'paragraph',
                        'props' => [
                            'html' => '<p>Thanks for joining <strong>{{company_name}}</strong>. We\'re excited to have you on board.</p><p>Click the button below to get started with your account.</p>',
                            'color' => '#555555',
                            'font_size' => 14,
                            'line_height' => 1.7,
                            'align' => 'left',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'button',
                        'props' => [
                            'text' => 'Get Started',
                            'url' => '{{app_url}}',
                            'bg_color' => '#378ADD',
                            'text_color' => '#ffffff',
                            'align' => 'center',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'footer',
                        'props' => [
                            'address' => '{{company_address}}',
                            'copyright' => '© {{company_name}}. All rights reserved.',
                            'unsubscribe_url' => '{{unsubscribe_url}}',
                            'unsubscribe_text' => 'Unsubscribe',
                            'bg_color' => '#f8f9fa',
                            'text_color' => '#666666',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Promotional Campaign',
                'slug' => 'promotional-campaign',
                'subject' => '{{discount}}% off — Limited time only',
                'preheader' => 'Don\'t miss our biggest sale of the year',
                'category' => TemplateCategory::Marketing,
                'category_slug' => 'marketing',
                'blocks' => [
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'preheader',
                        'props' => ['text' => 'Exclusive offer inside'],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'hero',
                        'props' => [
                            'title' => 'Mega Sale',
                            'subtitle' => 'Up to {{discount}}% off storewide',
                            'cta_text' => 'Shop Now',
                            'cta_url' => '{{shop_url}}',
                            'bg_color' => '#185FA5',
                            'align' => 'center',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'paragraph',
                        'props' => [
                            'html' => '<p>Hurry — offer ends <strong>{{end_date}}</strong>.</p>',
                            'color' => '#333333',
                            'align' => 'center',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'footer',
                        'props' => [
                            'address' => '{{company_address}}',
                            'copyright' => '© {{company_name}}. All rights reserved.',
                            'unsubscribe_url' => '{{unsubscribe_url}}',
                            'unsubscribe_text' => 'Unsubscribe',
                            'bg_color' => '#f8f9fa',
                            'text_color' => '#666666',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'subject' => 'Reset your password',
                'preheader' => 'We received a request to reset your password',
                'category' => TemplateCategory::Transactional,
                'category_slug' => 'transactional',
                'blocks' => [
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'heading',
                        'props' => [
                            'text' => 'Password Reset Request',
                            'level' => 'h1',
                            'color' => '#1a1a1a',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'paragraph',
                        'props' => [
                            'html' => '<p>Hi <strong>{{user_name}}</strong>, click the button below to reset your password.</p><p>This link expires in 60 minutes.</p>',
                            'color' => '#555555',
                            'font_size' => 14,
                            'line_height' => 1.7,
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'button',
                        'props' => [
                            'text' => 'Reset Password',
                            'url' => '{{reset_url}}',
                            'bg_color' => '#378ADD',
                            'text_color' => '#ffffff',
                            'align' => 'center',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'alert',
                        'props' => [
                            'type' => 'warning',
                            'text' => 'If you didn\'t request this, ignore this email — your password stays unchanged.',
                        ],
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'type' => 'footer',
                        'props' => [
                            'address' => '{{company_address}}',
                            'copyright' => '© {{company_name}}. All rights reserved.',
                            'unsubscribe_url' => '{{unsubscribe_url}}',
                            'unsubscribe_text' => 'Unsubscribe',
                            'bg_color' => '#f8f9fa',
                            'text_color' => '#666666',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($templates as $template) {
            $categorySlug = $template['category_slug'];
            unset($template['category_slug']);

            $category = CategoryModel::query()->where('slug', $categorySlug)->first();
            $template['category_id'] = $category?->id;
            $template['settings'] = ['primary_color' => '#3b82f6'];
            $template['status'] = TemplateStatus::Draft;
            $template['is_active'] = true;

            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template,
            );
        }
    }
}
