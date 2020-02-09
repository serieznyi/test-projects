<?php declare(strict_types=1);

namespace App\Infrastructure\Web;

use GuzzleHttp\Psr7\ServerRequest;

final class ProcessorArgsParser implements \App\Infrastructure\ProcessorArgsParser
{
    public function parse(): array
    {
        $request = new ServerRequest(
            $_SERVER['REQUEST_METHOD'],
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        $params = [];
        parse_str($_SERVER['QUERY_STRING'], $params);
        $request = $request->withQueryParams($params);

        return [$request];
    }
}