<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BlockType;
use App\Observers\PageBlockObserver;
use App\Support\BlockKeyGenerator;
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
        'key',
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
        // Every block gets a stable identifier, unique inside its page (default: the block type).
        static::saving(static function (PageBlock $block): void {
            if (blank($block->key)) {
                $block->key = BlockKeyGenerator::generate(
                    (int) $block->page_id,
                    $block->type?->value ?? 'block',
                    $block->exists ? (int) $block->getKey() : null,
                );
            }
        });

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
