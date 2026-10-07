<?php

use JeffersonGoncalves\MailEditor\Enums\TemplateStatus;
use JeffersonGoncalves\MailEditor\Models\EmailTemplate;

beforeEach(function () {
    $this->template = EmailTemplate::create([
        'name' => 'Workflow Test',
        'slug' => 'workflow-test',
        'subject' => 'Subject',
        'blocks' => [],
        'status' => TemplateStatus::Draft,
    ]);
});

it('starts in draft status', function () {
    expect($this->template->status)->toBe(TemplateStatus::Draft);
});

it('can be submitted for review', function () {
    $this->template->submitForReview();

    expect($this->template->fresh()->status)->toBe(TemplateStatus::Review);
});

it('can be approved', function () {
    $this->template->submitForReview();
    $this->template->approve('admin@test.com');

    $fresh = $this->template->fresh();
    expect($fresh->status)->toBe(TemplateStatus::Approved);
    expect($fresh->approved_by)->toBe('admin@test.com');
    expect($fresh->approved_at)->not->toBeNull();
});

it('can be rejected back to draft', function () {
    $this->template->submitForReview();
    $this->template->approve('admin@test.com');
    $this->template->rejectToDraft();

    $fresh = $this->template->fresh();
    expect($fresh->status)->toBe(TemplateStatus::Draft);
    expect($fresh->approved_by)->toBeNull();
    expect($fresh->approved_at)->toBeNull();
});

it('can be locked', function () {
    $this->template->lock('user@test.com');

    $fresh = $this->template->fresh();
    expect($fresh->locked_by)->toBe('user@test.com');
    expect($fresh->locked_at)->not->toBeNull();
});

it('can be unlocked', function () {
    $this->template->lock('user@test.com');
    $this->template->unlock();

    $fresh = $this->template->fresh();
    expect($fresh->locked_by)->toBeNull();
    expect($fresh->locked_at)->toBeNull();
});

it('detects lock by other user', function () {
    $this->template->lock('other@test.com');

    expect($this->template->isLockedByOther('me@test.com'))->toBeTrue();
    expect($this->template->isLockedByOther('other@test.com'))->toBeFalse();
});

it('is editable when draft and unlocked', function () {
    expect($this->template->isEditable('user@test.com'))->toBeTrue();
});

it('is not editable when approved', function () {
    $this->template->submitForReview();
    $this->template->approve('admin@test.com');

    expect($this->template->fresh()->isEditable('user@test.com'))->toBeFalse();
});

it('is not editable when locked by other', function () {
    $this->template->lock('other@test.com');

    expect($this->template->isEditable('me@test.com'))->toBeFalse();
});

it('has status constants', function () {
    expect(EmailTemplate::STATUS_DRAFT)->toBe('draft');
    expect(EmailTemplate::STATUS_REVIEW)->toBe('review');
    expect(EmailTemplate::STATUS_APPROVED)->toBe('approved');
});
