<?php

namespace JeffersonGoncalves\MailEditor\Enums;

enum TemplateCategory: string
{
    case Transactional = 'transactional';
    case Marketing = 'marketing';
    case Notification = 'notification';

    public function getLabel(): string
    {
        return __('mail-editor::mail-editor.template_categories.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Transactional => 'info',
            self::Marketing => 'success',
            self::Notification => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Transactional => 'heroicon-o-paper-airplane',
            self::Marketing => 'heroicon-o-megaphone',
            self::Notification => 'heroicon-o-bell',
        };
    }
}
