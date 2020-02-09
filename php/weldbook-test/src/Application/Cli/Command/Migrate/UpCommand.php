<?php declare(strict_types=1);

namespace App\Application\Cli\Command\Migrate;

use App\Infrastructure\Migrate;

final class UpCommand
{
    /**
     * @var Migrate
     */
    private Migrate $migrate;

    public function __construct(Migrate $migrate)
    {
        $this->migrate = $migrate;
    }

    public function __invoke(): void
    {
        $this->migrate->upAll();
    }
}