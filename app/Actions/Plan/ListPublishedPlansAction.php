<?php

declare(strict_types=1);

namespace App\Actions\Plan;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

final class ListPublishedPlansAction
{
    /**
     * @return Collection<int, Plan>
     */
    public function execute(): Collection
    {
        return Plan::query()->published()->ordered()->get();
    }
}
