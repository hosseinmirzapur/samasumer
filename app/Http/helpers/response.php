<?php


use Illuminate\Http\JsonResponse;

if (!function_exists('successResponse')) {
    /**
     * A helper function for successful response
     *
     * @param array $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    function successResponse(array $data = [], string $message = "SUCCESS", int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'success' => true,
            "message" => $message
        ], $statusCode);
    }
}
