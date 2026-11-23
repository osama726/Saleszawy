<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ButtonVariant;
use App\Enums\MenuItemKind;
use App\Observers\MenuItemObserver;
use App\Traits\HasSortOrder;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([MenuItemObserver::class])]
class MenuItem extends Model
{
    /** @use HasFactory<MenuItemFactory> */
    use HasFactory;
    use HasSortOrder;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'kind',
        'variant',
        'opens_in_new_tab',
        'is_visible',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'kind' => MenuItemKind::class,
            'variant' => ButtonVariant::class,
            'opens_in_new_tab' => 'boolean',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Menu, $this>
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @return BelongsTo<MenuItem, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Visible children (dropdown entries), ordered.
     *
     * @return HasMany<MenuItem, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
