<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\IntegrationPayloadService;
use Illuminate\Http\JsonResponse;

final class IntegrationController extends ApiController
{
    public function index(IntegrationPayloadService $integrations): JsonResponse
    {
        return $this->success($integrations->all());
    }
}
