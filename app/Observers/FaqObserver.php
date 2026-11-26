<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Faq;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class FaqObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(Faq $faq): void
    {
        // Moved to another group: the old group's list changes too.
        if ($faq->exists && $faq->isDirty('group')) {
            $original = $faq->getOriginal('group');
            $this->forget(is_string($original) ? $original : null);
        }
    }

    public function saved(Faq $faq): void
    {
        $this->forget($faq->group);
    }

    public function deleted(Faq $faq): void
    {
        $this->forget($faq->group);
    }

    private function forget(?string $group): void
    {
        $this->cache->forget(['faqs'], CacheKeys::faqs(null));

        if ($group !== null && $group !== '') {
            $this->cache->forget(['faqs'], CacheKeys::faqs($group));
        }
    }
}
