<?php

namespace JeffersonGoncalves\MailEditor\Enums;

enum ScheduleStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Sent = 'sent';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return __('mail-editor::mail-editor.schedule_statuses.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Processing => 'info',
            self::Sent => 'success',
            self::Failed => 'danger',
            self::Cancelled => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Processing => 'heroicon-o-arrow-path',
            self::Sent => 'heroicon-o-check-circle',
            self::Failed => 'heroicon-o-exclamation-triangle',
            self::Cancelled => 'heroicon-o-x-circle',
        };
    }
}
