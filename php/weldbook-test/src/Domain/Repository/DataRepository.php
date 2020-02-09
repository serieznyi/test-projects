<?php declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Data;

interface DataRepository
{
    /**
     * @param array $params
     * @param int $page
     * @param int $limit
     * @return Data[]
     */
    public function findAllBy(array $params, int $page, int $limit): array;

    public function count(array $params): int;
}