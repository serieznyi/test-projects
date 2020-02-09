<?php declare(strict_types=1);

namespace App\Infrastructure\Web;

use GuzzleHttp\Psr7\Response;

final class JsonResponse extends Response
{
    public function __construct(int $status = 200, array $headers = [], $jsonData = [], string $version = '1.1', string $reason = null)
    {
        $headers['Content-Type'] = 'application/json';

        parent::__construct(
            $status,
            $headers,
            $jsonData,
            $version,
            $reason
        );
    }
}