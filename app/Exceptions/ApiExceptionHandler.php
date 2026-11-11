<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Chuẩn hóa lỗi cho mọi request /api/*:
 *   { "status": false, "message": "...", "errors": {...} (chỉ khi validate lỗi) }
 * Mặc định 400; map riêng 204/401/403/404/405/422; lỗi khác giữ status của
 * HttpException, còn lỗi hệ thống trả 500 với message chung (không lộ stack trace).
 */
class ApiExceptionHandler
{
    public function render(Throwable $th): JsonResponse
    {
        $statusCode = JsonResponse::HTTP_BAD_REQUEST;
        $message = __('message.errors.bad_request');
        $errors = null;

        switch (true) {
            case $th instanceof NoContentException:
                return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
            case $th instanceof AuthenticationException:
                $message = __('message.errors.unauthorized');
                $statusCode = JsonResponse::HTTP_UNAUTHORIZED;
                break;
            case $th instanceof AuthorizationException:
            case $th instanceof AccessDeniedHttpException:
                $message = __('message.errors.forbidden');
                $statusCode = JsonResponse::HTTP_FORBIDDEN;
                break;
            case $th instanceof NotFoundHttpException:
                $message = __('message.errors.page_not_found');
                $statusCode = JsonResponse::HTTP_NOT_FOUND;
                break;
            case $th instanceof ModelNotFoundException:
                $message = __('message.errors.model_not_found');
                $statusCode = JsonResponse::HTTP_NOT_FOUND;
                break;
            case $th instanceof MethodNotAllowedHttpException:
                $message = __('message.errors.method_not_allowed');
                $statusCode = JsonResponse::HTTP_METHOD_NOT_ALLOWED;
                break;
            case $th instanceof ValidationException:
                $message = __('message.errors.request_invalid');
                $statusCode = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;
                $errors = $th->validator->errors();
                break;
            case $th instanceof HttpExceptionInterface:
                $statusCode = $th->getStatusCode();
                $message = $th->getMessage() ?: $message;
                break;
            default:
                // Lỗi hệ thống: chỉ hiện chi tiết khi APP_DEBUG=true.
                $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
                $message = config('app.debug') ? $th->getMessage() : __('message.errors.server_error');
                break;
        }

        return $this->makeJsonResponse(code: $statusCode, message: $message, errors: $errors);
    }

    private function makeJsonResponse(int $code, string $message, $errors): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
