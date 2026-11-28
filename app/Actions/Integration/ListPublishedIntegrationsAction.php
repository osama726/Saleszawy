<?php

declare(strict_types=1);

namespace App\Actions\Integration;

use App\Models\Integration;
use Illuminate\Database\Eloquent\Collection;

final class ListPublishedIntegrationsAction
{
    /**
     * @return Collection<int, Integration>
     */
    public function execute(): Collection
    {
        return Integration::query()->published()->ordered()->get();
    }
}
