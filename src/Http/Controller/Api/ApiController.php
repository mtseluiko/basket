<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 19.11.18
 * Time: 15:12
 */

namespace App\Http\Controller\Api;


use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController
{
    protected function successResponse(array $data, int $statusCode = JsonResponse::HTTP_OK) : JsonResponse
    {
        return JsonResponse::create(['data' => $data], $statusCode);
    }
    protected function successResponseWithMeta(array $data, int $statusCode = JsonResponse::HTTP_OK) : JsonResponse
    {
        return JsonResponse::create([
            'data' => $data
        ], $statusCode);
    }
    protected function errorResponse(string $data, int $statusCode = JsonResponse::HTTP_BAD_REQUEST) : JsonResponse
    {
        $errorData = [
            'error' => [
                'httpStatus' => $statusCode,
                'message' => $data
            ]
        ];
        return JsonResponse::create($errorData, $statusCode);
    }
    protected function emptyResponse(int $statusCode = 200): JsonResponse
    {
        return JsonResponse::create(null, $statusCode);
    }
}