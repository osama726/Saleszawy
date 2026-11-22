<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Maps every exception raised on /api/* to the unified error envelope.
 * Registered from bootstrap/app.php via ->withExceptions(...).
 */
final class ApiExceptionRenderer
{
    public static function register(Exceptions $exceptions): void
    {
        $exceptions->shouldRenderJsonWhen(
            static fn (Request $request, Throwable $e): bool => self::isApi($request) || $request->expectsJson(),
        );

        $exceptions->render(static function (ValidationException $e, Request $request): ?JsonResponse {
            return self::isApi($request)
                ? self::json('البيانات المدخلة غير صحيحة', 'VALIDATION_ERROR', 422, $e->errors())
                : null;
        });

        $exceptions->render(static function (AuthenticationException $e, Request $request): ?JsonResponse {
            return self::isApi($request)
                ? self::json('يجب تسجيل الدخول أولاً', 'UNAUTHENTICATED', 401)
                : null;
        });

        $exceptions->render(static function (AuthorizationException $e, Request $request): ?JsonResponse {
            return self::isApi($request)
                ? self::json('غير مصرح لك بتنفيذ هذا الإجراء', 'FORBIDDEN', 403)
                : null;
        });

        $exceptions->render(static function (ModelNotFoundException $e, Request $request): ?JsonResponse {
            return self::isApi($request)
                ? self::json('العنصر المطلوب غير موجود', 'NOT_FOUND', 404)
                : null;
        });

        $exceptions->render(static function (NotFoundHttpException $e, Request $request): ?JsonResponse {
            return self::isApi($request)
                ? self::json('العنصر المطلوب غير موجود', 'NOT_FOUND', 404)
                : null;
        });

        $exceptions->render(static function (ThrottleRequestsException $e, Request $request): ?JsonResponse {
            return self::isApi($request)
                ? self::json('عدد الطلبات كبير، حاول مرة أخرى بعد قليل', 'TOO_MANY_REQUESTS', 429)
                : null;
        });

        $exceptions->render(static function (Throwable $e, Request $request): ?JsonResponse {
            if (! self::isApi($request)) {
                return null;
            }

            if ($e instanceof HttpExceptionInterface) {
                return self::json($e->getMessage() ?: 'تعذر تنفيذ الطلب', 'HTTP_ERROR', $e->getStatusCode());
            }

            // In debug mode let Laravel show the real error.
            if (config('app.debug')) {
                return null;
            }

            return self::json('حدث خطأ غير متوقع', 'SERVER_ERROR', 500);
        });
    }

    private static function isApi(Request $request): bool
    {
        return $request->is('api/*');
    }

    /**
     * @param  array<string, mixed>  $errors
     */
    private static function json(string $message, string $code, int $status, array $errors = []): JsonResponse
    {
        return response()->json(array_filter([
            'success' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors ?: null,
        ], static fn (mixed $value): bool => $value !== null), $status);
    }
}
