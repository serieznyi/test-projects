<?php declare(strict_types=1);

namespace App\Infrastructure\Web;

use JsonException;
use Throwable;

final class ExceptionHandler implements \App\Infrastructure\ExceptionHandler
{
    /**
     * @param Throwable $throwable
     * @throws JsonException
     */
    public function handle(Throwable $throwable): void
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 0,
            'error' => [
                (string)$throwable
            ],
            'data' => [],
        ], JSON_THROW_ON_ERROR, 512);
    }
}