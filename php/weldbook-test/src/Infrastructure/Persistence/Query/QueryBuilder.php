<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Query;

use InvalidArgumentException;

final class QueryBuilder
{
    private string $tableName;

    private array $whereArgs = [];

    private bool $isCountMode = false;

    private array $orderBy = [];

    private array $select = ['*'];

    private int $limit = 0;

    private int $offset = 0;

    private function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param string $tableName
     * @return static create instance of builder
     */
    public static function create(string $tableName): self
    {
        return new self($tableName);
    }

    public function select(array $fields): self
    {
        if (!$fields) {
            throw new InvalidArgumentException('`select` cant be empty');
        }

        $this->select = $fields;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function tableName(string $tableName): self
    {
        if (!$tableName) {
            throw new InvalidArgumentException('tableName cant be empty');
        }

        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param string $fieldName
     * @param int $direction SORT_ASC, SORT_DESC
     * @return $this
     */
    public function sort(string $fieldName, int $direction): self
    {
        $this->orderBy = [$fieldName, $direction];

        return $this;
    }

    public function whereArgs(array $args): self
    {
        $this->whereArgs = $args;

        return $this;
    }

    public function count(): self
    {
        $this->isCountMode = true;

        return $this;
    }

    /**
     * @return PdoQuery buildQuery
     */
    public function build(): PdoQuery
    {
        $pdoParams = [];

        $query = ['SELECT'];

        // COUNT(*) or choose fields or *

        if (true === $this->isCountMode) {
            $query[] = 'COUNT(*)';
        } else {

            $fields = [];
            foreach ($this->select as $name) {
                if ($fields) {
                    $fields[] = ',';
                }
                $fields[] = $name;
            }

            $query = array_merge($query, $fields);
        }

        // FROM

        $query[] = 'FROM';

        if (!$this->tableName) {
            throw new InvalidArgumentException('tableName is empty');
        }

        $query[] = $this->tableName;

        // WHERE

        if ($this->whereArgs) {
            $where = [];
            foreach ($this->whereArgs as $name => $value) {
                if ($where) {
                    $where[] = 'AND';
                }

                $keyName = $this->prepareParamName($name);

                // Приходится приводить к строке все т.к. PDO не позволяет указать в качестве типа DOUBLE
                $where[] = "CONVERT(`$name`, char)";
                $where[] = '=';
                $where[] = $keyName;

                $pdoParams[$keyName] = $value;
            }

            if ($where) {
                $query = array_merge($query, ['WHERE'], $where);
            }
        }

        // ORDER BY

        if ($this->orderBy) {
            foreach ($this->orderBy as $field => $direction) {
                $query[] = 'ORDER BY';
                $query[] = $field;
                $query[] = $direction === SORT_ASC ? 'ASC' : 'DESC';

                $pdoParams[$this->prepareParamName($field)] = $field;
            }
        }

        // LIMIT

        if ($this->limit) {
            $query[] = 'LIMIT';
            $query[] = ':limit';
        }

        // OFFSET

        if ($this->offset) {
            $query[] = 'OFFSET';
            $query[] = ':offset';
        }

        return new PdoQuery(
            implode(' ', $query),
            $pdoParams,
            $this->limit,
            $this->offset
        );
    }

    /**
     * Удаляем не стандартные символы в названиях т.к. PDO почему то они не нравятся
     * @param $name
     * @return string
     */
    private function prepareParamName($name): string
    {
        return ':' . str_replace(['-', '_'], '', $name);
    }
}