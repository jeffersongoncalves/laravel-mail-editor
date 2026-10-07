<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateScheduleFactory;
use JeffersonGoncalves\MailEditor\Enums\ScheduleStatus;

/**
 * @property int $id
 * @property int $template_id
 * @property ScheduleStatus $status
 * @property Carbon $scheduled_at
 * @property Carbon|null $sent_at
 * @property string $recipients_type
 * @property array<int, string>|array<string, mixed> $recipients
 * @property array<string, mixed>|null $variables
 * @property int|null $variant_id
 * @property string|null $scheduled_by
 * @property string|null $cancelled_by
 * @property Carbon|null $cancelled_at
 * @property string|null $cancel_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read EmailTemplate $template
 * @property-read EmailTemplateVariant|null $variant
 *
 * @method static Builder<static> pending()
 * @method static Builder<static> due()
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateScheduleFactory factory($count = null, $state = [])
 */
class EmailTemplateSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): Factory
    {
        return EmailTemplateScheduleFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'template_id',
        'status',
        'scheduled_at',
        'sent_at',
        'recipients_type',
        'recipients',
        'variables',
        'variant_id',
        'scheduled_by',
        'cancelled_by',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'status' => ScheduleStatus::class,
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'recipients' => 'array',
        'variables' => 'array',
    ];

    public function getTable(): string
    {
        return 'email_template_schedules';
    }

    /** @return BelongsTo<EmailTemplate, $this> */
    public function template(): BelongsTo
    {
        /** @var class-string<EmailTemplate> $model */
        $model = config('mail-editor.model', EmailTemplate::class);

        return $this->belongsTo($model, 'template_id');
    }

    /** @return BelongsTo<EmailTemplateVariant, $this> */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(EmailTemplateVariant::class, 'variant_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ScheduleStatus::Pending);
    }

    public function scopeDue(Builder $query): Builder
    {
        return $query->where('status', ScheduleStatus::Pending)
            ->where('scheduled_at', '<=', now());
    }

    public function cancel(?string $cancelledBy = null, ?string $reason = null): void
    {
        $this->update([
            'status' => ScheduleStatus::Cancelled,
            'cancelled_by' => $cancelledBy,
            'cancelled_at' => now(),
            'cancel_reason' => $reason,
        ]);
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => ScheduleStatus::Sent,
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => ScheduleStatus::Failed]);
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => ScheduleStatus::Processing]);
    }
}
