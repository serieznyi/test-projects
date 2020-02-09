<?php declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Exception;

final class RouteNotFound extends Exception
{
    public static function createDefault(string $uri): self
    {
        return new self("Route not found: $uri");
    }
}