<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\PartnerPayloadService;
use Illuminate\Http\JsonResponse;

final class PartnerController extends ApiController
{
    public function index(PartnerPayloadService $partners): JsonResponse
    {
        return $this->success($partners->all());
    }
}
