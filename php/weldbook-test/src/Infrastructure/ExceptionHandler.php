<?php declare(strict_types=1);

namespace App\Infrastructure;

use Throwable;

interface ExceptionHandler
{
    public function handle(Throwable $throwable): void;
}