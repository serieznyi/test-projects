<?php declare(strict_types=1);

namespace App\Infrastructure;

interface ProcessorArgsParser
{
    public function parse(): array;
}