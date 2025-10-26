<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    /**
     * Create a successful response.
     */
    public static function success(
        int $status = Response::HTTP_OK,
        string $message = 'Success',
        mixed $data = null,
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Create an error response.
     */
    public static function error(
        int $status = Response::HTTP_BAD_REQUEST,
        string $message = 'Error',
        mixed $data = null,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
