<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\SavedEmailBlockFactory;
use JeffersonGoncalves\MailEditor\Enums\BlockCategory;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property string $type
 * @property array<string, mixed> $props
 * @property int|null $user_id
 * @property bool $is_global
 * @property BlockCategory|null $category
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static Builder<static> global()
 * @method static Builder<static> forUser(int $userId)
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\SavedEmailBlockFactory factory($count = null, $state = [])
 */
class SavedEmailBlock extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): Factory
    {
        return SavedEmailBlockFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'name',
        'description',
        'thumbnail',
        'type',
        'props',
        'user_id',
        'is_global',
        'category',
    ];

    protected $casts = [
        'props' => 'array',
        'is_global' => 'boolean',
        'category' => BlockCategory::class,
    ];

    public function getTable(): string
    {
        return 'saved_email_blocks';
    }

    public function scopeGlobal(Builder $query): Builder
    {
        return $query->where('is_global', true);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where(function (Builder $q) use ($userId) {
            $q->where('user_id', $userId)->orWhere('is_global', true);
        });
    }
}
