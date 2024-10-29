<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Throwable;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send a standardized success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function sendResponse($data = null, string $message = 'Operation successful', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Send a standardized error response.
     *
     * @param Throwable $exception
     * @param string|null $message
     * @param int|null $statusCode
     * @return JsonResponse
     */
    protected function sendError(Throwable $exception, ?string $message = null, ?int $statusCode = null): JsonResponse
    {
        // Default to internal server error if no specific status code provided
        $statusCode = $statusCode ?? $this->getStatusCodeForException($exception);

        return response()->json([
            'success' => false,
            'message' => $message ?? $exception->getMessage(),
        ], $statusCode);
    }

    /**
     * Map exceptions to appropriate HTTP status codes.
     *
     * @param Throwable $exception
     * @return int
     */
    private function getStatusCodeForException(Throwable $exception): int
    {
        return match (true) {
            $exception instanceof \Illuminate\Validation\ValidationException => 422,
            $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 404,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 404,
            $exception instanceof \Illuminate\Auth\AuthenticationException => 401,
            $exception instanceof \Illuminate\Auth\Access\AuthorizationException => 403,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 503 => 503,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 502 => 502,
            $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 504 => 504,
            default => 500,
        };
    }

    /**
     * Validate request data and return validated data.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     */
    protected function validateRequest(array $data, array $rules, array $messages = [], array $customAttributes = []): array
    {
        return validator($data, $rules, $messages, $customAttributes)->validate();
    }
}
