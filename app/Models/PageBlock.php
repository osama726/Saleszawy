<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BlockType;
use App\Observers\PageBlockObserver;
use Database\Factories\PageBlockFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([PageBlockObserver::class])]
class PageBlock extends Model
{
    /** @use HasFactory<PageBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'page_id',
        'type',
        'data',
        'sort_order',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'type' => BlockType::class,
            'data' => 'array',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // New blocks go to the end of the page unless an order is given.
        static::creating(static function (PageBlock $block): void {
            if (! $block->sort_order) {
                $block->sort_order = (int) static::query()
                    ->where('page_id', $block->page_id)
                    ->max('sort_order') + 1;
            }
        });
    }

    /**
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
