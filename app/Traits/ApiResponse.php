<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * @param  array<string, mixed>  $meta
     */
    protected function success(mixed $data = null, string $message = '', int $status = 200, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => (object) $meta,
        ], $status);
    }

    protected function created(mixed $data = null, string $message = ''): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * @param  class-string<\Illuminate\Http\Resources\Json\JsonResource>  $resource
     */
    protected function paginated(LengthAwarePaginator $paginator, string $resource, string $message = ''): JsonResponse
    {
        return $this->success(
            $resource::collection($paginator->getCollection()),
            $message,
            200,
            [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $errors
     */
    protected function error(string $message, string $code, int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json(array_filter([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors ?: null,
        ], static fn (mixed $value): bool => $value !== null), $status);
    }
}
