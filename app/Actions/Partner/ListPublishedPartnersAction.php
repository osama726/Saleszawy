<?php

declare(strict_types=1);

namespace App\Actions\Partner;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Collection;

final class ListPublishedPartnersAction
{
    /**
     * @return Collection<int, Partner>
     */
    public function execute(): Collection
    {
        return Partner::query()
            ->published()
            ->ordered()
            ->with('media')
            ->get();
    }
}
