<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateNotificationFactory;
use JeffersonGoncalves\MailEditor\Enums\NotificationType;

/**
 * @property int $id
 * @property int $template_id
 * @property NotificationType $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string|null $message
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EmailTemplate $template
 * @property-read Model $notifiable
 *
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateNotificationFactory factory($count = null, $state = [])
 */
class EmailTemplateNotification extends Model
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return EmailTemplateNotificationFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'template_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'type' => NotificationType::class,
        'read_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return 'email_template_notifications';
    }

    /** @return BelongsTo<EmailTemplate, $this> */
    public function template(): BelongsTo
    {
        /** @var class-string<EmailTemplate> $model */
        $model = config('mail-editor.model', EmailTemplate::class);

        return $this->belongsTo($model, 'template_id');
    }

    /** @return MorphTo<Model, $this> */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
