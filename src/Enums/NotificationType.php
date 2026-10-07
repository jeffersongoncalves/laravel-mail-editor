<?php

namespace JeffersonGoncalves\MailEditor\Enums;

enum NotificationType: string
{
    case SubmittedForReview = 'submitted_for_review';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function getLabel(): string
    {
        return __('mail-editor::mail-editor.notification_types.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SubmittedForReview => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::SubmittedForReview => 'heroicon-o-paper-airplane',
            self::Approved => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
        };
    }
}
