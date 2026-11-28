<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ListPostsRequest;
use App\Services\PostPayloadService;
use Illuminate\Http\JsonResponse;

final class PostController extends ApiController
{
    public function index(ListPostsRequest $request, PostPayloadService $posts): JsonResponse
    {
        return $this->success($posts->all(
            $request->category(),
            $request->tag(),
            $request->search(),
            $request->featured(),
            $request->limit(),
        ));
    }

    public function show(string $slug, PostPayloadService $posts): JsonResponse
    {
        return $this->success($posts->show($slug));
    }
}
