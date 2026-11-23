<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\PlanObserver;
use App\Traits\HasPublishStatus;
use App\Traits\HasSortOrder;
use Database\Factories\PlanFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PlanObserver::class])]
class Plan extends Model
{
    /** @use HasFactory<PlanFactory> */
    use HasFactory;
    use HasPublishStatus;
    use HasSortOrder;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'currency',
        'trial_days',
        'max_sales_users',
        'max_leads',
        'features',
        'is_featured',
        'badge',
        'cta_label',
        'cta_url',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
            'trial_days' => 'integer',
            'max_sales_users' => 'integer',
            'max_leads' => 'integer',
            'features' => 'array',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'status' => ContentStatus::class,
        ];
    }
}
