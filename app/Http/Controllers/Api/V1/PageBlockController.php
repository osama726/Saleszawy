<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\Blocks\BlockContentService;
use App\Services\PagePayloadService;
use Illuminate\Http\JsonResponse;

final class PageBlockController extends ApiController
{
    public function show(string $slug, string $key, PagePayloadService $pages, BlockContentService $content): JsonResponse
    {
        return $this->success($content->enrich($pages->block($slug, $key)));
    }
}
