<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MenuLocation;
use App\Observers\MenuObserver;
use App\Traits\HasSortOrder;
use Database\Factories\MenuFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([MenuObserver::class])]
class Menu extends Model
{
    /** @use HasFactory<MenuFactory> */
    use HasFactory;
    use HasSortOrder;

    protected $fillable = [
        'key',
        'title',
        'location',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'location' => MenuLocation::class,
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    /**
     * All items (admin), ordered.
     *
     * @return HasMany<MenuItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Visible top-level items (public API); load `rootItems.children` for dropdowns.
     *
     * @return HasMany<MenuItem, $this>
     */
    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * @param  Builder<Menu>  $query
     * @return Builder<Menu>
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }
}
