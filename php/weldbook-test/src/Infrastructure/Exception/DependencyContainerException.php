<?php declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Exception;

final class DependencyContainerException extends Exception
{
    public static function createDefault(string $objectName): self
    {
        return new self("Can`t find dependency: $objectName");
    }
}