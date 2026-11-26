<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\FeatureCategoryObserver;
use App\Traits\HasSortOrder;
use Database\Factories\FeatureCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([FeatureCategoryObserver::class])]
class FeatureCategory extends Model
{
    /** @use HasFactory<FeatureCategoryFactory> */
    use HasFactory;
    use HasSortOrder;

    protected $fillable = [
        'name',
        'slug',
        'group',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<Feature, $this>
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class, 'category_id')->orderBy('sort_order')->orderBy('id');
    }
}
