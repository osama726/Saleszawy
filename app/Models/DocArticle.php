<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\DocArticleObserver;
use App\Traits\HasSortOrder;
use Database\Factories\DocArticleFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([DocArticleObserver::class])]
class DocArticle extends Model
{
    /** @use HasFactory<DocArticleFactory> */
    use HasFactory;
    use HasSortOrder;
    use SoftDeletes;

    protected $fillable = [
        'section_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'version',
        'status',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'section_id' => 'integer',
            'sort_order' => 'integer',
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<DocSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(DocSection::class, 'section_id');
    }

    /**
     * Published and not scheduled for the future.
     *
     * @param  Builder<DocArticle>  $query
     * @return Builder<DocArticle>
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
