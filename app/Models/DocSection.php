<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\DocSectionObserver;
use App\Traits\HasPublishStatus;
use App\Traits\HasSortOrder;
use Database\Factories\DocSectionFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([DocSectionObserver::class])]
class DocSection extends Model
{
    /** @use HasFactory<DocSectionFactory> */
    use HasFactory;
    use HasPublishStatus;
    use HasSortOrder;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'icon',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'parent_id' => 'integer',
            'sort_order' => 'integer',
            'status' => ContentStatus::class,
        ];
    }

    /**
     * @return BelongsTo<DocSection, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * All child sections (admin), ordered.
     *
     * @return HasMany<DocSection, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order')->orderBy('id');
    }

    /**
     * All articles (admin), ordered.
     *
     * @return HasMany<DocArticle, $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(DocArticle::class, 'section_id')->orderBy('sort_order')->orderBy('id');
    }
}
