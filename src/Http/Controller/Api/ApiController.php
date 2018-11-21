<?php
/**
 * Created by PhpStorm.
 * User: mikhail
 * Date: 19.11.18
 * Time: 15:12
 */

namespace App\Http\Controller\Api;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController
{
    protected function successResponse(array $data, int $statusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        return new JsonResponse(['data' => $data], $statusCode);
    }

    protected function successResponseWithMeta(array $data, int $statusCode = JsonResponse::HTTP_OK): JsonResponse
    {
        return new JsonResponse(['data' => $data], $statusCode);
    }

    protected function errorResponse(string $data, int $statusCode = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        $errorData = [
            'error' => [
                'httpStatus' => $statusCode,
                'message' => $data
            ]
        ];
        return new JsonResponse($errorData, $statusCode);
    }

    protected function emptyResponse(int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(null, $statusCode);
    }
}