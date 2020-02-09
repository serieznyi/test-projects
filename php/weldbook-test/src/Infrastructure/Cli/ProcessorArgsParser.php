<?php declare(strict_types=1);

namespace App\Infrastructure\Cli;

final class ProcessorArgsParser implements \App\Infrastructure\ProcessorArgsParser
{
    public function parse(): array
    {
        $argv = $_SERVER['argv'];

        // Remove script file name from args
        array_shift($argv);
        // Remove uri name
        array_shift($argv);

        return $argv;
    }
}