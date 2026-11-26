<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ListFeaturesRequest;
use App\Services\FeaturePayloadService;
use Illuminate\Http\JsonResponse;

final class FeatureController extends ApiController
{
    public function index(ListFeaturesRequest $request, FeaturePayloadService $features): JsonResponse
    {
        return $this->success($features->all($request->group(), $request->category()));
    }
}
