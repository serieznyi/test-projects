<?php declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Infrastructure\ResponseRenderer;
use Psr\Http\Message\ResponseInterface;

final class WebResponseRenderer implements ResponseRenderer
{
    /**
     * @param ResponseInterface $response
     * @return void
     */
    public function render($response): void
    {
        foreach ($response->getHeaders() as $headerName => $headerValues) {
            foreach ($headerValues as $value) {
                header($headerName . ':' . $value);
            }
        }
        echo $response->getBody()->getContents();
    }
}