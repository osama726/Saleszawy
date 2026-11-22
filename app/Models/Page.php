<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\PageObserver;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([PageObserver::class])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'seo_title',
        'seo_description',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * All blocks (admin), ordered.
     *
     * @return HasMany<PageBlock, $this>
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    /**
     * Visible blocks only (public API), ordered.
     *
     * @return HasMany<PageBlock, $this>
     */
    public function visibleBlocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)
            ->where('is_visible', true)
            ->orderBy('sort_order');
    }

    /**
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', ContentStatus::Published->value)
            ->where(static function (Builder $q): void {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }
}
