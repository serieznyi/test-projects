<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Exception;

use App\Infrastructure\Exception\ArrayOfMessagesException;
use Exception;

class UnknownColumnDatabaseException extends Exception implements ArrayOfMessagesException
{
    private array $columnNames;

    public function __construct(string $tableName, array $columnNames)
    {
        $this->columnNames = $columnNames;

        parent::__construct(
            sprintf(
                'Next columns does not exists in table schema. Table `%s`, columns: %s',
                $tableName,
                implode(', ', $columnNames)
            )
        );
    }

    public static function createWithColumnNames(string $tableName, array $columnNames): self {
        return new self($tableName, $columnNames);
    }

    public function getColumnNames(): array
    {
        return $this->columnNames;
    }

    public function toArrayOfMessages(): array
    {
        $columns = $this->columnNames;

        $errors = [];
        foreach ($columns as $column) {
            $errors[$column] = 'Данное поле не существует';
        }

        return $errors;
    }
}