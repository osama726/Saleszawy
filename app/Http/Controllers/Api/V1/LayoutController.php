<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\LayoutPayloadService;
use Illuminate\Http\JsonResponse;

final class LayoutController extends ApiController
{
    public function __construct(private readonly LayoutPayloadService $layout) {}

    public function index(): JsonResponse
    {
        return $this->success($this->layout->all());
    }

    public function header(): JsonResponse
    {
        return $this->success($this->layout->header());
    }

    public function footer(): JsonResponse
    {
        return $this->success($this->layout->footer());
    }
}
