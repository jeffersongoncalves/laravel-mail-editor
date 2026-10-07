# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 - 2026-10-06

First release.

Block-based email templates for Laravel, extracted from [filament-mail-editor](https://github.com/jeffersongoncalves/filament-mail-editor) so the core works without Filament:

- 22 email-safe blocks rendered to table-based, inline-CSS HTML (VML for Outlook, consolidated media queries, dark mode) plus a plain-text version
- Template variables with fallbacks, conditionals and loops
- Review workflow with editing lock, version history, A/B variants, brand kits and themes
- Accessibility, spam score, link and quality checks
- `Mail::template($slug, $variables)` integration, JSON import/export, Artisan commands

```bash
composer require jeffersongoncalves/laravel-mail-editor

```