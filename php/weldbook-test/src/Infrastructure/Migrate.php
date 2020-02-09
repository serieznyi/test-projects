<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Infrastructure\Exception\MigrationException;
use App\Infrastructure\Persistence\Database;
use RuntimeException;
use Throwable;

final class Migrate
{
    private const MIGRATION_TABLE_NAME = 'migrations';

    private Database $database;

    private string $sqlFilesDir;

    public function __construct(Database $database, string $sqlFilesDir)
    {
        $this->database = $database;

        $this->sqlFilesDir = rtrim($sqlFilesDir) . DIRECTORY_SEPARATOR;
        if (!file_exists($sqlFilesDir)) {
            throw new RuntimeException('Directory not exists: ' . $sqlFilesDir);
        }
    }

    public function upAll(): void
    {
        $this->prepareMigrationsTable();

        $migrations = $this->findNotImportedMigrations();

        $this->applyMigrations($migrations);
    }

    private function prepareMigrationsTable(): void
    {
        $table = self::MIGRATION_TABLE_NAME;

        $this->database->exec(
            <<<EOF
CREATE TABLE IF NOT EXISTS {$table} (
id int(11) NOT NULL AUTO_INCREMENT,
file varchar(255) NOT NULL,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_general_ci;
EOF

        );
    }

    private function findNotImportedMigrations(): array
    {
        $sqlFiles = $this->findAllMigrations();

        $registeredMigrations = $this->getRegisteredMigrations();

        return array_diff($sqlFiles, $registeredMigrations);
    }

    private function findAllMigrations(): array
    {
        return array_diff(scandir($this->sqlFilesDir), ['..', '.']);
    }

    private function getRegisteredMigrations(): array
    {
        $registeredMigrations = $this->database->findAll(self::MIGRATION_TABLE_NAME);

        return array_map(static function (array $row) {
            return $row['file'];
        }, $registeredMigrations);
    }

    private function applyMigrations(array $files): void
    {
        $this->prepareMigrationsTable();

        if (!$files) {
            echo "All migrations already imported ... \n";
        }

        foreach ($files as $fileName) {
            echo "Try apply migrations: $fileName ... ";

            $absoluteFileName = $this->sqlFilesDir . DIRECTORY_SEPARATOR . $fileName;

            try {
                $this->database->beginTransaction();

                $fileContent = file_get_contents($absoluteFileName);
                $this->database->exec($fileContent);
                $this->database->insertRow(self::MIGRATION_TABLE_NAME, ['file' => basename($fileName)]);

                $this->database->commitTransaction();
                echo " OK \n";
            } catch (Throwable $exception) {
                $this->database->rollbackTransaction();

                echo " FAIL \n";

                throw MigrationException::createDefault($exception);
            }
        }
    }
}