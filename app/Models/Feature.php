<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\FeatureObserver;
use App\Traits\HasPublishStatus;
use App\Traits\HasSortOrder;
use Database\Factories\FeatureFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([FeatureObserver::class])]
class Feature extends Model
{
    /** @use HasFactory<FeatureFactory> */
    use HasFactory;
    use HasPublishStatus;
    use HasSortOrder;

    protected $fillable = [
        'category_id',
        'group',
        'title',
        'slug',
        'summary',
        'badge',
        'description',
        'points',
        'how_it_works',
        'icon',
        'cta_label',
        'cta_url',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'category_id' => 'integer',
            'points' => 'array',
            'how_it_works' => 'array',
            'sort_order' => 'integer',
            'status' => ContentStatus::class,
        ];
    }

    /**
     * @return BelongsTo<FeatureCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FeatureCategory::class, 'category_id');
    }
}
