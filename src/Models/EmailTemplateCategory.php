<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateCategoryFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $color
 * @property string|null $icon
 * @property string|null $description
 * @property int|null $parent_id
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read EmailTemplateCategory|null $parent
 * @property-read Collection<int, EmailTemplateCategory> $children
 * @property-read Collection<int, EmailTemplate> $templates
 *
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailTemplateCategoryFactory factory($count = null, $state = [])
 */
class EmailTemplateCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): Factory
    {
        return EmailTemplateCategoryFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
        'description',
        'parent_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getTable(): string
    {
        return 'email_template_categories';
    }

    /** @return BelongsTo<EmailTemplateCategory, $this> */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** @return HasMany<EmailTemplateCategory, $this> */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    /** @return HasMany<EmailTemplate, $this> */
    public function templates(): HasMany
    {
        /** @var class-string<EmailTemplate> $model */
        $model = config('mail-editor.model', EmailTemplate::class);

        return $this->hasMany($model, 'category_id');
    }

    /**
     * Get full breadcrumb path (e.g., "Marketing > Newsletters").
     */
    public function getFullPath(): string
    {
        $parts = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($parts, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $parts);
    }
}
