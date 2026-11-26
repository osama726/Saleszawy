<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

final class ListPublishedFaqsAction
{
    /**
     * @return Collection<int, Faq>
     */
    public function execute(?string $group = null): Collection
    {
        return Faq::query()
            ->published()
            ->when($group !== null, static fn ($query) => $query->where('group', $group))
            ->ordered()
            ->get();
    }
}
