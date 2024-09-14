<?php

namespace Jasmin\Core\Database\SchemaBuilder;

use PDO;
use Jasmin\Core\Helpers\Filesystem\Dir;
use Jasmin\Core\Database\DatabaseResolver;
use Jasmin\Core\Database\ConnectionManager;
use Jasmin\Core\Database\SchemaBuilder\Blueprints\Table;

class MigrationsRunner
{
    protected ?PDO $conn = null;

    public function __construct(protected string $projectPath)
    {
        $this->conn = ConnectionManager::getConnection('test');
    }

    protected const REL_MIGRATIONS_PATH = 'migrations';

    public function run()
    {
        $migrations = array_map(
            fn ($e) => Migration::create($e),
            Dir::files($this->getAbsMigrationsPath(), true)
        );

        die();

        $lastMigratedFile = $this->getLastMigratedFile();

        // if last migrated file exists, run migrations with dates after that
        // otherwise run all migrations
        $migrationsToRun = Migration::since($migrations, $lastMigratedFile);

        foreach ($migrationsToRun as $migrationToRun) {
            (Migration::createClassFromFile($migrationToRun))->migrate();
        }


        var_dump($lastMigratedFile);

        // run migrations
    }

    protected function getMigrationFiles(): array
    {
        $absMigrationPath = $this->getAbsMigrationsPath();
        return array_map(fn ($e) => $absMigrationPath . '/' . $e, scandir($absMigrationPath));
    }

    protected function getAbsMigrationsPath(): string
    {
        return $this->projectPath . '/' . static::REL_MIGRATIONS_PATH;
    }

    protected function getLastMigratedFile(): ?string
    {
        $stmt = $this->conn->query("SELECT * FROM migrations ORDER BY timestamp DESC LIMIT 1", PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }
}
