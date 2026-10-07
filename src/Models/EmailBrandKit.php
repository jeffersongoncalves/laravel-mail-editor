<?php

namespace JeffersonGoncalves\MailEditor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\MailEditor\Database\Factories\EmailBrandKitFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_default
 * @property string|null $logo_url
 * @property string|null $logo_alt
 * @property array{primary_color: string, secondary_color: string, accent_color: string, bg_color: string, content_bg: string, text_color: string, muted_color: string, button_bg: string, button_text: string} $colors
 * @property array{font_family: string, font_size_base: int, line_height_base: float, border_radius: int} $typography
 * @property array<int, array{platform: string, url: string}>|null $social_links
 * @property string|null $footer_address
 * @property string|null $unsubscribe_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static Builder<static> default()
 * @method static \JeffersonGoncalves\MailEditor\Database\Factories\EmailBrandKitFactory factory($count = null, $state = [])
 */
class EmailBrandKit extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory(): Factory
    {
        return EmailBrandKitFactory::new();
    }

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'is_default',
        'logo_url',
        'logo_alt',
        'colors',
        'typography',
        'social_links',
        'footer_address',
        'unsubscribe_url',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'colors' => 'array',
        'typography' => 'array',
        'social_links' => 'array',
    ];

    public function getTable(): string
    {
        return 'email_brand_kits';
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the default brand kit, or null if none is set.
     */
    public static function getDefault(): ?self
    {
        return self::where('is_default', true)->first();
    }

    /**
     * Convert the brand kit to a theme-compatible array.
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

    /**
     * Set this brand kit as the default (and unset any other).
     */
    public function setAsDefault(): void
    {
        self::where('is_default', true)->update(['is_default' => false]);
        $this->update(['is_default' => true]);
    }
}
