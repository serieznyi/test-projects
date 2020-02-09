<?php declare(strict_types=1);

namespace App\Infrastructure\Cli;

use App\Infrastructure\ResponseRenderer;

final class CliResponseRenderer implements ResponseRenderer
{
    /**
     * @param string $response
     * @return void
     */
    public function render($response): void
    {
        echo $response;
    }
}