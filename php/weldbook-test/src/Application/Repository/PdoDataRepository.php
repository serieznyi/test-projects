<?php declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\Mapper\DataMapper;
use App\Domain\Entity\Data;
use App\Domain\Repository\DataRepository;
use App\Infrastructure\Persistence\Database;

final class PdoDataRepository implements DataRepository
{
    private const TABLE_NAME = 'test';

    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param array $params
     * @param int $page
     * @param int $limit
     * @return Data[]
     * @throws \ReflectionException
     */
    public function findAllBy(array $params, int $page, int $limit): array
    {
        $rows = $this->database->findAllPerPage(
            self::TABLE_NAME,
            $params,
            $page,
            $limit
        );

        $data = [];

        $mapper = new DataMapper();

        foreach ($rows as $row) {
            $entity = new Data(1, '', '', []);

            $mapper->mapData($entity, $row);

            $data[] = $entity;
        }

        return $data;
    }

    public function count(array $params): int
    {
        return $this->database->count(
            self::TABLE_NAME,
            $params
        );
    }
}