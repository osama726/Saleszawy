<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Actions\Doc\SearchDocArticlesAction;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\SearchDocsRequest;
use App\Http\Resources\DocSearchResultResource;
use App\Services\DocPayloadService;
use Illuminate\Http\JsonResponse;

final class DocController extends ApiController
{
    public function tree(DocPayloadService $docs): JsonResponse
    {
        return $this->success($docs->tree());
    }

    public function show(string $section, string $article, DocPayloadService $docs): JsonResponse
    {
        return $this->success($docs->article($section, $article));
    }

    public function search(SearchDocsRequest $request, SearchDocArticlesAction $search): JsonResponse
    {
        return $this->success(
            DocSearchResultResource::collection($search->execute($request->term())),
            meta: ['query' => $request->term()],
        );
    }
}
