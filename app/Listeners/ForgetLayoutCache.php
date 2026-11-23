<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Services\CacheService;
use App\Settings\FooterSettings;
use App\Settings\GeneralSettings;
use App\Support\CacheKeys;
use Spatie\LaravelSettings\Events\SettingsSaved;

/**
 * Auto-discovered by Laravel (typed handle() argument).
 */
final class ForgetLayoutCache
{
    public function __construct(private readonly CacheService $cache) {}

    public function handle(SettingsSaved $event): void
    {
        if ($event->settings instanceof GeneralSettings || $event->settings instanceof FooterSettings) {
            $this->cache->forget(['layout'], CacheKeys::layout());
        }
    }
}
