<?php

namespace JeffersonGoncalves\MailEditor\Enums;

enum BlockCategory: string
{
    case Structure = 'structure';
    case Content = 'content';
    case Marketing = 'marketing';

    public function getLabel(): string
    {
        return __('mail-editor::mail-editor.block_categories.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Structure => 'gray',
            self::Content => 'info',
            self::Marketing => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Structure => 'heroicon-o-rectangle-stack',
            self::Content => 'heroicon-o-document-text',
            self::Marketing => 'heroicon-o-megaphone',
        };
    }
}
