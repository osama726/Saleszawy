<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Feature;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class FeatureObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(Feature $feature): void
    {
        if ($feature->exists && $feature->isDirty('group')) {
            $original = $feature->getOriginal('group');
            $this->forget(is_string($original) ? $original : null);
        }
    }

    public function saved(Feature $feature): void
    {
        $this->forget($feature->group);
    }

    public function deleted(Feature $feature): void
    {
        $this->forget($feature->group);
    }

    private function forget(?string $group): void
    {
        $this->cache->forget(['features'], CacheKeys::features(null));

        if ($group !== null && $group !== '') {
            $this->cache->forget(['features'], CacheKeys::features($group));
        }
    }
}
