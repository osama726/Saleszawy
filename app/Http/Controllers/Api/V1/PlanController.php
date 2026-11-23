<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\PlanPayloadService;
use Illuminate\Http\JsonResponse;

final class PlanController extends ApiController
{
    public function index(PlanPayloadService $plans): JsonResponse
    {
        return $this->success($plans->all());
    }
}
