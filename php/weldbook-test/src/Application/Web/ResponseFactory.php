<?php

namespace App\Application\Web;

use App\Application\DataSet;
use App\Infrastructure\Web\JsonResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface;

final class ResponseFactory
{
    /**
     * @param DataSet $dataSet
     * @return ResponseInterface
     * @throws JsonException
     */
    public static function createSuccessResponse(DataSet $dataSet): ResponseInterface
    {
        $jsonData = [
            'status' => 1,
            'error' => '',
            'data' => $dataSet,
        ];

        $json = json_encode($jsonData, JSON_THROW_ON_ERROR, 512);

        return new JsonResponse(200, [], $json);
    }

    public static function createErrorValidationResponse(array $errors): ResponseInterface
    {
        $jsonData = [
            'status' => 0,
            'error' => [
                'message' => 'Validation Error',
                'errors' => $errors,
            ],
        ];

        $json = json_encode($jsonData, JSON_THROW_ON_ERROR, 512);

        return new JsonResponse(200, [], $json);
    }
}