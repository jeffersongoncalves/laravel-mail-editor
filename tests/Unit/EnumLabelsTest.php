<?php

use JeffersonGoncalves\MailEditor\Enums\ActivityAction;
use JeffersonGoncalves\MailEditor\Enums\BlockCategory;
use JeffersonGoncalves\MailEditor\Enums\NotificationType;
use JeffersonGoncalves\MailEditor\Enums\ScheduleStatus;
use JeffersonGoncalves\MailEditor\Enums\TemplateCategory;
use JeffersonGoncalves\MailEditor\Enums\TemplateStatus;

it('translates every enum case from the package translations', function (string $enum) {
    foreach ($enum::cases() as $case) {
        expect($case->getLabel())->not->toStartWith('mail-editor::');
    }
})->with([
    TemplateStatus::class,
    TemplateCategory::class,
    BlockCategory::class,
    ScheduleStatus::class,
    NotificationType::class,
    ActivityAction::class,
]);

it('uses the configured locale', function () {
    app()->setLocale('pt_BR');

    expect(TemplateStatus::Draft->getLabel())->toBe('Rascunho');
});
