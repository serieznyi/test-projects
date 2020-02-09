<?php declare(strict_types=1);

namespace App\Infrastructure\Cli;

use Throwable;

final class ExceptionHandler implements \App\Infrastructure\ExceptionHandler
{
    public function handle(Throwable $throwable): void
    {
        echo $throwable;
    }
}