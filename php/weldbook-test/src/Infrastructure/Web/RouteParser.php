<?php declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Infrastructure\Exception\RouteNotFound;

final class RouteParser implements \App\Infrastructure\RouteParser
{
    public function parse(array $registeredRoutes): string
    {
        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $requestUri = rtrim($urlPath, '/');

        foreach (array_keys($registeredRoutes) as $pattern) {
            $updatedPattern = str_replace('/', '\/', $pattern);
            $updatedPattern = preg_replace('/\{\w+\}/', "\w+", $updatedPattern);

            if (preg_match("/^$updatedPattern$/", $requestUri, $asd)) {
                return $pattern;
            }

        }

        throw RouteNotFound::createDefault($requestUri);
    }
}