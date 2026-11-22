<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\PagePayloadService;
use Illuminate\Http\JsonResponse;

final class PageBlockController extends ApiController
{
    public function show(string $slug, string $key, PagePayloadService $pages): JsonResponse
    {
        return $this->success($pages->block($slug, $key));
    }
}
