<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ListFaqsRequest;
use App\Services\FaqPayloadService;
use Illuminate\Http\JsonResponse;

final class FaqController extends ApiController
{
    public function index(ListFaqsRequest $request, FaqPayloadService $faqs): JsonResponse
    {
        return $this->success($faqs->all($request->group()));
    }
}
