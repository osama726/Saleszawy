<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ContentStatus;
use App\Observers\IntegrationObserver;
use App\Traits\HasPublishStatus;
use App\Traits\HasSortOrder;
use Database\Factories\IntegrationFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([IntegrationObserver::class])]
class Integration extends Model
{
    /** @use HasFactory<IntegrationFactory> */
    use HasFactory;
    use HasPublishStatus;
    use HasSortOrder;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'tags',
        'icon',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'sort_order' => 'integer',
            'status' => ContentStatus::class,
        ];
    }
}
