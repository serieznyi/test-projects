<?php declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Exception;
use Throwable;

final class MigrationException extends Exception
{
    public static function createDefault(Throwable $exception): self
    {
        return new self($exception->getMessage(), 0, $exception);
    }
}