<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateFactory;
use JeffersonGoncalves\MailEditor\Enums\ActivityAction;
use JeffersonGoncalves\MailEditor\Enums\TemplateCategory;
use JeffersonGoncalves\MailEditor\Enums\TemplateStatus;
use JeffersonGoncalves\MailEditor\Events\TemplateLocked;
use JeffersonGoncalves\MailEditor\Events\TemplateStatusChanged;
use JeffersonGoncalves\MailEditor\Events\TemplateUnlocked;
use JeffersonGoncalves\MailEditor\Events\VersionCreated;
use JeffersonGoncalves\MailEditor\Events\VersionRestored;
use JeffersonGoncalves\MailEditor\Support\HtmlExporter;
use JeffersonGoncalves\MailEditor\Support\VariableEngine;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $subject
 * @property string|null $preheader
 * @property array<int, array{id: string, type: string, props: array<string, mixed>}> $blocks
 * @property array<string, mixed>|null $settings
 * @property TemplateCategory|null $category
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static Builder<static> active()
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateFactory factory($count = null, $state = [])
 *
 * @property TemplateStatus $status
 * @property string|null $locked_by
 * @property Carbon|null $locked_at
 * @property Carbon|null $lock_expires_at
 * @property string|null $approved_by
 * @property Carbon|null $approved_at
 * @property int|null $category_id
 * @property-read EmailTemplateCategory|null $templateCategory
 * @property-read Collection<int, EmailTemplateVariant> $variants
 * @property-read Collection<int, EmailTemplateVersion> $versions
 * @property-read Collection<int, EmailTemplateActivity> $activities
 * @property-read Collection<int, EmailTemplateNotification> $notifications
 * @property-read Collection<int, EmailTemplateSchedule> $schedules
 */
class EmailTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): Factory
    {
        return EmailTemplateFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'preheader',
        'blocks',
        'settings',
        'category',
        'category_id',
        'is_active',
        'status',
        'locked_by',
        'locked_at',
        'lock_expires_at',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'blocks' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'category' => TemplateCategory::class,
        'status' => TemplateStatus::class,
        'locked_at' => 'datetime',
        'lock_expires_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return config('mail-editor.table_name', 'email_templates');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** @return BelongsTo<EmailTemplateCategory, $this> */
    public function templateCategory(): BelongsTo
    {
        return $this->belongsTo(EmailTemplateCategory::class, 'category_id');
    }

    /** @return HasMany<EmailTemplateVariant, $this> */
    public function variants(): HasMany
    {
        return $this->hasMany(EmailTemplateVariant::class, 'template_id');
    }

    /** @return HasMany<EmailTemplateVersion, $this> */
    public function versions(): HasMany
    {
        return $this->hasMany(EmailTemplateVersion::class, 'template_id');
    }

    /** @return HasMany<EmailTemplateActivity, $this> */
    public function activities(): HasMany
    {
        return $this->hasMany(EmailTemplateActivity::class, 'template_id');
    }

    /** @return HasMany<EmailTemplateNotification, $this> */
    public function notifications(): HasMany
    {
        return $this->hasMany(EmailTemplateNotification::class, 'template_id');
    }

    /** @return HasMany<EmailTemplateSchedule, $this> */
    public function schedules(): HasMany
    {
        return $this->hasMany(EmailTemplateSchedule::class, 'template_id');
    }

    public function logActivity(ActivityAction $action, ?array $metadata = null): EmailTemplateActivity
    {
        return $this->activities()->create([
            'action' => $action,
            'performed_by' => self::resolveCurrentUserName(),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Create a version snapshot of the current state.
     */
    public function createVersion(?string $reason = null, ?string $createdBy = null): EmailTemplateVersion
    {
        $latestVersion = $this->versions()->max('version_number') ?? 0;

        $version = $this->versions()->create([
            'version_number' => $latestVersion + 1,
            'blocks' => $this->blocks ?? [],
            'settings' => $this->settings,
            'subject' => $this->subject,
            'preheader' => $this->preheader,
            'reason' => $reason,
            'created_by' => $createdBy,
            'created_at' => now(),
        ]);

        VersionCreated::dispatch($version);

        return $version;
    }

    // ──── Workflow ────

    public const STATUS_DRAFT = 'draft';

    public const STATUS_REVIEW = 'review';

    public const STATUS_APPROVED = 'approved';

    public function submitForReview(): void
    {
        $this->transitionTo(TemplateStatus::Review);
        $this->logActivity(ActivityAction::SubmittedForReview);
    }

    public function approve(?string $approvedBy = null): void
    {
        $this->transitionTo(TemplateStatus::Approved);
        $this->update([
            'approved_by' => $approvedBy ?? self::resolveCurrentUserName(),
            'approved_at' => now(),
        ]);
        $this->logActivity(ActivityAction::Approved);
    }

    public function rejectToDraft(): void
    {
        $this->transitionTo(TemplateStatus::Draft);
        $this->update([
            'approved_by' => null,
            'approved_at' => null,
        ]);
        $this->logActivity(ActivityAction::Rejected);
    }

    protected function transitionTo(TemplateStatus $target): void
    {
        $oldStatus = $this->status;
        $this->status->transitionTo($target);
        $this->update(['status' => $target]);

        TemplateStatusChanged::dispatch($this, $oldStatus, $target);
    }

    // ──── Lock ────

    public function lock(?string $lockedBy = null): void
    {
        $timeout = config('mail-editor.lock_timeout', 30);
        $lockedBy = $lockedBy ?? self::resolveCurrentUserName();

        $this->update([
            'locked_by' => $lockedBy,
            'locked_at' => now(),
            'lock_expires_at' => now()->addMinutes($timeout),
        ]);

        TemplateLocked::dispatch($this, $lockedBy ?? '');
    }

    public function unlock(): void
    {
        $this->update([
            'locked_by' => null,
            'locked_at' => null,
            'lock_expires_at' => null,
        ]);

        TemplateUnlocked::dispatch($this);
    }

    public function isLockExpired(): bool
    {
        if (! $this->lock_expires_at) {
            return false;
        }

        return $this->lock_expires_at->isPast();
    }

    public function isLockedByOther(?string $currentUser = null): bool
    {
        if (! $this->locked_by) {
            return false;
        }

        if ($this->isLockExpired()) {
            $this->unlock();

            return false;
        }

        $currentUser = $currentUser ?? self::resolveCurrentUserName();

        return $this->locked_by !== $currentUser;
    }

    public function isEditable(?string $currentUser = null): bool
    {
        if ($this->status === TemplateStatus::Approved) {
            return false;
        }

        return ! $this->isLockedByOther($currentUser);
    }

    /**
     * Restore template to a specific version.
     */
    public function restoreVersion(int $versionId): void
    {
        $version = $this->versions()->findOrFail($versionId);

        $this->update([
            'blocks' => $version->blocks,
            'settings' => $version->settings,
            'subject' => $version->subject,
            'preheader' => $version->preheader,
        ]);

        VersionRestored::dispatch($this, $version);
    }

    protected static function resolveCurrentUserName(): ?string
    {
        /** @var (Authenticatable&object{name?: string, email?: string})|null $user */
        $user = auth()->user();

        return $user->name ?? $user->email ?? null;
    }

    public function render(array $variables = []): string
    {
        $engine = new VariableEngine;
        $blocks = $this->blocks ?? [];

        $blocks = array_map(function (array $block) use ($engine, $variables) {
            $block['props'] = $engine->processProps($block['props'], $variables);

            return $block;
        }, $blocks);

        return app(HtmlExporter::class)->export($blocks, $this->settings ?? []);
    }
}
