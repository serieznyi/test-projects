<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Infrastructure\Persistence\Exception\UnknownColumnDatabaseException;
use App\Infrastructure\Persistence\Query\QueryBuilder;
use PDO;

class Database
{
    private static array $instances = [];

    private PDO $connection;

    /**
     * Database constructor.
     * @param string $scheme
     * @param string $user
     * @param string $pass
     */
    private function __construct($scheme, $user, $pass)
    {
        $this->connection = new PDO($scheme, $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string $scheme
     * @param string $user
     * @param string $password
     * @return Database
     */
    public static function instance($scheme, $user, $password): Database
    {
        $key = "$scheme:$user:$password";

        if (!array_key_exists($key, static::$instances)) {
            self::$instances[$key] = new self($scheme, $user, $password);
        }

        return self::$instances[$key];
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->connection->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->connection->rollBack();
    }

    public function insertRow(string $tableName, array $data): void
    {
        $sql = "INSERT INTO {$tableName} (%s) VALUES (%s)";
        $columns = '';
        $valuesMask = '';

        foreach (array_keys($data) as $key) {
            $columns .= $key . ',';
            $valuesMask .= '?,';
        }

        $sql = sprintf($sql, rtrim($columns, ','), rtrim($valuesMask, ','));

        $this
            ->connection
            ->prepare($sql)
            ->execute(array_values($data));
    }

    public function exec(string $sql): void
    {
        $this->connection->exec($sql);
    }

    public function findAll(string $tableName): array
    {
        return $this->findAllBy($tableName, []);
    }

    public function findAllBy($tableName, $filter): array
    {
        $this->checkWhereArgs($tableName, $filter);

        $query = QueryBuilder::create($tableName)
            ->whereArgs($filter)
            ->build();

        $stat = $this->connection->prepare($query->getQuery());
        $stat->execute($query->getPdoParams());

        $data = $stat->fetchAll(PDO::FETCH_ASSOC);

        return $data === false ? [] : $data;
    }

    public function findAllPerPage(string $tableName, array $filter, int $page, int $limit): array
    {
        $this->checkWhereArgs($tableName, $filter);

        $query = QueryBuilder::create($tableName)
            ->whereArgs($filter)
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->build();

        $stat = $this->connection->prepare($query->getQuery());

        if ($limit = $query->getLimit()) {
            $stat->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset = $query->getOffset()) {
            $stat->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        foreach ($query->getPdoParams() as $key => $value) {
            $stat->bindValue($key, (string)$value, PDO::PARAM_STR);
        }

        $stat->execute();

        $data = $stat->fetchAll(PDO::FETCH_ASSOC);

        return $data === false ? [] : $data;
    }

    public function count(string $tableName, array $filter = []): int
    {
        $this->checkWhereArgs($tableName, $filter);

        $query = QueryBuilder::create($tableName)
            ->whereArgs($filter)
            ->count()
            ->build();

        $stat = $this->connection->prepare($query->getQuery());

        foreach ($query->getPdoParams() as $key => $value) {
            $stat->bindValue($key, (string)$value, PDO::PARAM_STR);
        }

        $stat->execute();

        return (int)$stat->fetchColumn();
    }

    private function getTableFieldNames(string $tableName): array
    {
        // TODO cache data key-value storage
        $statement = $this->connection->query('DESCRIBE ' . $tableName);

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            static function (array $fieldMeta) {
                return $fieldMeta['Field'];
            },
            $result
        );
    }

    private function checkWhereArgs(string $tableName, array $whereArgs): void
    {
        $tableColumnNames = $this->getTableFieldNames($tableName);

        $whereArgs = array_filter(
            $whereArgs,
            static function ($fieldName) use ($tableColumnNames) {
                return !in_array($fieldName, $tableColumnNames, true);
            },
            ARRAY_FILTER_USE_KEY
        );

        if ($whereArgs) {
            throw UnknownColumnDatabaseException::createWithColumnNames($tableName, array_keys($whereArgs));
        }
    }
}