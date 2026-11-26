<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\FeatureCategory;
use App\Services\CacheService;
use App\Support\CacheKeys;

final class FeatureCategoryObserver
{
    public function __construct(private readonly CacheService $cache) {}

    public function saving(FeatureCategory $category): void
    {
        if ($category->exists && $category->isDirty('group')) {
            $original = $category->getOriginal('group');
            $this->forget(is_string($original) ? $original : null);
        }
    }

    public function saved(FeatureCategory $category): void
    {
        $this->forget($category->group);
    }

    public function deleted(FeatureCategory $category): void
    {
        $this->forget($category->group);
    }

    private function forget(?string $group): void
    {
        $this->cache->forget(['features'], CacheKeys::features(null));

        foreach (array_unique(array_filter(['home', 'features', $group])) as $key) {
            $this->cache->forget(['features'], CacheKeys::features($key));
        }
    }
}
