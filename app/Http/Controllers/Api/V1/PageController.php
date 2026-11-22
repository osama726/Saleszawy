<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\Page\GetPublishedPageAction;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\PageResource;
use App\Services\CacheService;
use App\Support\CacheKeys;
use Illuminate\Http\JsonResponse;

final class PageController extends ApiController
{
    private const TTL = 3600;

    public function __construct(private readonly CacheService $cache) {}

    public function show(string $slug, GetPublishedPageAction $action): JsonResponse
    {
        // Cache the final array (not Eloquent models) so nothing heavy is serialized.
        $data = $this->cache->remember(
            ['pages'],
            CacheKeys::page($slug),
            self::TTL,
            static fn (): array => json_decode(
                (string) json_encode(PageResource::make($action->execute($slug))),
                true,
                512,
                JSON_THROW_ON_ERROR,
            ),
        );

        return $this->success($data);
    }
}
