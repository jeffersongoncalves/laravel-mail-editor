<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailThemeFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_default
 * @property bool $is_system
 * @property array{primary_color: string, secondary_color: string, accent_color: string, bg_color: string, content_bg: string, text_color: string, muted_color: string, button_bg: string, button_text: string} $colors
 * @property array{font_family: string, font_size_base: int, line_height_base: float, border_radius: int} $typography
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static Builder<static> default()
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailThemeFactory factory($count = null, $state = [])
 */
class EmailTheme extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): Factory
    {
        return EmailThemeFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'is_default',
        'is_system',
        'colors',
        'typography',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_system' => 'boolean',
        'colors' => 'array',
        'typography' => 'array',
    ];

    public function getTable(): string
    {
        return 'email_themes';
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Flattened theme settings (colors + typography merged) for ThemeApplier.
     *
     * @return array<string, mixed>
     */
    public function toThemeArray(): array
    {
        return array_merge(
            $this->colors ?? [],
            $this->typography ?? [],
        );
    }

    public function setAsDefault(): void
    {
        self::where('is_default', true)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}
