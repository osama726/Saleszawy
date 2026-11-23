<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasSortOrder
{
    protected static function bootHasSortOrder(): void
    {
        // New records go to the end unless an order is given.
        static::creating(static function (Model $model): void {
            if (! $model->getAttribute('sort_order')) {
                $model->setAttribute('sort_order', (int) static::query()->max('sort_order') + 1);
            }
        });
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
