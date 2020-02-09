<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Infrastructure\Exception\RouteNotFound;

interface RouteParser
{
    /**
     * @param array $registeredRoutes
     * @return string
     * @throws RouteNotFound
     */
    public function parse(array $registeredRoutes): string;
}