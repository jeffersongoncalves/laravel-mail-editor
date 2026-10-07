<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateVariantFactory;

/**
 * @property int $id
 * @property int $template_id
 * @property string $name
 * @property array<int, array{id: string, type: string, props: array<string, mixed>}> $blocks
 * @property array<string, mixed>|null $settings
 * @property int $send_percentage
 * @property bool $is_winner
 * @property int $sends_count
 * @property int $opens_count
 * @property int $clicks_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateVariantFactory factory($count = null, $state = [])
 */
class EmailTemplateVariant extends Model
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return EmailTemplateVariantFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'template_id',
        'name',
        'blocks',
        'settings',
        'send_percentage',
        'is_winner',
        'sends_count',
        'opens_count',
        'clicks_count',
    ];

    protected $casts = [
        'blocks' => 'array',
        'settings' => 'array',
        'send_percentage' => 'integer',
        'is_winner' => 'boolean',
        'sends_count' => 'integer',
        'opens_count' => 'integer',
        'clicks_count' => 'integer',
    ];

    public function getTable(): string
    {
        return 'email_template_variants';
    }

    /** @return BelongsTo<EmailTemplate, $this> */
    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }
}
