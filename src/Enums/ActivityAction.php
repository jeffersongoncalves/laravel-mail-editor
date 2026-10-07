<?php

namespace JeffersonGoncalves\MailEditor\Enums;

enum ActivityAction: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case SubmittedForReview = 'submitted_for_review';
    case Exported = 'exported';
    case Locked = 'locked';
    case Unlocked = 'unlocked';
    case VersionCreated = 'version_created';
    case VersionRestored = 'version_restored';
    case TestSent = 'test_sent';
    case BrandKitApplied = 'brand_kit_applied';
    case ThemeApplied = 'theme_applied';
    case Imported = 'imported';

    public function getLabel(): string
    {
        return __('mail-editor::mail-editor.activity_actions.'.$this->value);
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Created => 'heroicon-o-plus-circle',
            self::Updated => 'heroicon-o-pencil-square',
            self::Approved => 'heroicon-o-check-circle',
            self::Rejected => 'heroicon-o-x-circle',
            self::SubmittedForReview => 'heroicon-o-paper-airplane',
            self::Exported => 'heroicon-o-arrow-down-tray',
            self::Locked => 'heroicon-o-lock-closed',
            self::Unlocked => 'heroicon-o-lock-open',
            self::VersionCreated => 'heroicon-o-document-duplicate',
            self::VersionRestored => 'heroicon-o-arrow-uturn-left',
            self::TestSent => 'heroicon-o-envelope',
            self::BrandKitApplied => 'heroicon-o-paint-brush',
            self::ThemeApplied => 'heroicon-o-swatch',
            self::Imported => 'heroicon-o-arrow-up-tray',
        };
    }
}
