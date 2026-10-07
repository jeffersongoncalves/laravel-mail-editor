<?php

namespace JeffersonGoncalves\MailEditor\Enums;

use JeffersonGoncalves\MailEditor\Exceptions\InvalidStatusTransition;

enum TemplateStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Approved = 'approved';

    public function getLabel(): string
    {
        return __('mail-editor::mail-editor.template_status.'.$this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Review => 'warning',
            self::Approved => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-pencil',
            self::Review => 'heroicon-o-clock',
            self::Approved => 'heroicon-o-check-circle',
        };
    }

    /** @return list<self> */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Draft => [self::Review],
            self::Review => [self::Approved, self::Draft],
            self::Approved => [self::Draft],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions());
    }

    public function transitionTo(self $target): self
    {
        if (! $this->canTransitionTo($target)) {
            throw InvalidStatusTransition::from($this->value, $target->value);
        }

        return $target;
    }
}
