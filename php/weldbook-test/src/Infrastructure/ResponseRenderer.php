<?php declare(strict_types=1);

namespace App\Infrastructure;

interface ResponseRenderer
{
    public function render($response): void;
}