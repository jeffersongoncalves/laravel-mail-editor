<div class="filament-hidden">

![Laravel Mail Editor](https://raw.githubusercontent.com/jeffersongoncalves/laravel-mail-editor/main/art/jeffersongoncalves-laravel-mail-editor.png)

</div>

# Laravel Mail Editor

[![Buy Me A Coffee](https://img.shields.io/badge/Buy%20Me%20A%20Coffee-support-FFDD00?style=flat-square&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/jeffersongoncalves)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-mail-editor.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-mail-editor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-mail-editor/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-mail-editor/actions?query=workflow%3Atests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-mail-editor/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-mail-editor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-mail-editor.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-mail-editor)

Block-based email templates for Laravel. Templates are stored as an ordered list of blocks and rendered to table-based, inline-CSS HTML that works in every major email client (Gmail, Outlook, Apple Mail), with template variables, a review workflow, version history, A/B variants, brand kits, themes and quality checks.

This is the framework-agnostic core. For a visual drag-and-drop editor in your admin panel, use [jeffersongoncalves/filament-mail-editor](https://github.com/jeffersongoncalves/filament-mail-editor), which is built on top of this package.

## Features

- **22 email-safe blocks** across three categories: structure, content, marketing
- **Table-based HTML export** with inline CSS via `CssToInlineStyles`, VML for Outlook, consolidated media queries and dark mode
- **Plain-text version** generated from the same blocks for `multipart/alternative` emails
- **Template variables** — `{{var}}`, fallbacks `{{var|default}}`, conditionals `{{#if}}`, loops `{{#each}}`
- **Workflow** — draft → review → approved, with concurrent editing lock
- **Version history** — immutable snapshots with restore
- **A/B testing** — variants with send percentage split and winner flag
- **Brand kits and themes** — reusable logo, colors, typography and social links
- **Saved block library** — per-user or global reusable blocks
- **Quality checks** — accessibility (WCAG), spam score, link validation, preheader/alt text
- **Laravel Mail integration** — `Mail::template($slug, $variables)->to(...)->send()`
- **JSON import/export** of templates

## Requirements

- PHP `^8.3`
- Laravel `^11.28`, `^12.0` or `^13.0`
- `ext-gd` (for countdown image rendering)

## Installation

```bash
composer require jeffersongoncalves/laravel-mail-editor
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="mail-editor-migrations"
php artisan migrate
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="mail-editor-config"
```

Seed demo data (optional):

```bash
php artisan db:seed --class="JeffersonGoncalves\MailEditor\Database\Seeders\MailEditorSeeder"
```

## Usage

### Send a template email

```php
use Illuminate\Support\Facades\Mail;

Mail::template('welcome-email', [
    'user_name' => $user->name,
    'app_url' => config('app.url'),
])->to($user->email)->send();
```

### Render a template to HTML

```php
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

$html = EmailTemplate::where('slug', 'welcome-email')
    ->first()
    ->render(['user_name' => 'Alice']);
```

### Generate a template via Artisan

```bash
php artisan mail-editor:make-template "Welcome Email"
```

### Release stale editing locks

```bash
php artisan mail-editor:release-locks
```

### Import and export

```php
use JeffersonGoncalves\MailEditor\Support\TemplateImportExport;

$json = (new TemplateImportExport)->exportJson($template);
$copy = (new TemplateImportExport)->importJson($json);
```

## Block Catalog

**Structure:** Preheader · Header · Footer · Spacer
**Content:** Heading · Paragraph · Button · Image · Divider · List · Alert · TwoColumns · ThreeColumns
**Marketing:** Hero · ProductCard · Coupon · Testimonial · Rating · VideoThumb · DataTable · Countdown · LogoGrid

### Register a custom block

```php
// config/mail-editor.php
'blocks' => [
    \App\Mail\Blocks\MyCustomBlock::class,
],
```

Your block must implement `JeffersonGoncalves\MailEditor\Blocks\Contracts\EmailBlock` (or extend `AbstractEmailBlock`) and ship a Blade view at `resources/views/vendor/mail-editor/blocks/{type}.blade.php`. Block templates must use `<table role="presentation">` layout with inline styles.

## Template Variables

```
Hello {{user_name|there}}!

{{#if is_premium}}
  You have premium access.
{{#else}}
  Upgrade anytime.
{{/if}}

{{#each items}}
  - {{this.name}}: {{this.price}}
{{/each}}
```

## Preview Routes

The package registers two routes under the `mail-editor` prefix, used by editors to render live previews:

- `GET /mail-editor/preview` (`mail-editor.preview`) — renders blocks inside an iframe
- `GET /mail-editor/countdown` (`mail-editor.countdown`) — generates the countdown timer PNG

Their middleware is configured by `mail-editor.preview_route_middleware` (default `['web']`).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
