<?php

namespace App\Infrastructure\Cli;

use App\Infrastructure\Exception\RouteNotFound;

final class RouteParser implements \App\Infrastructure\RouteParser
{
    public function parse(array $registeredRoutes): string
    {
        $argv = $_SERVER['argv'];

        // Remove script file name from args
        array_shift($argv);

        $uri = array_shift($argv);

        foreach (array_keys($registeredRoutes) as $pattern) {
            $updatedPattern = str_replace('/', '\/', $pattern);
            $updatedPattern = preg_replace('/\{\w+\}/', "\w+", $updatedPattern);

            if (preg_match("/^$updatedPattern$/", $uri, $asd)) {
                return $pattern;
            }

        }

        throw RouteNotFound::createDefault($uri);
    }
}