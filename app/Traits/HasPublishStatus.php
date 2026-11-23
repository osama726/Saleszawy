<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasPublishStatus
{
    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ContentStatus::Published->value);
    }
}
