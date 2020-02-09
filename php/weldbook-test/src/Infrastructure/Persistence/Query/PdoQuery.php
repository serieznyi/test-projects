<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Query;

final class PdoQuery
{
    private string $query;

    private array $pdoParams;

    private int $limit;

    private int $offset;

    public function __construct(string $query, array $pdoParams, int $limit, int $offset)
    {
        $this->query = $query;
        $this->pdoParams = $pdoParams;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getPdoParams(): array
    {
        return $this->pdoParams;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}