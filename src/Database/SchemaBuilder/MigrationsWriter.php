<?php

namespace Jasmin\Core\Database\SchemaBuilder;

class MigrationsWriter
{
    public function __construct(protected string $projectPath)
    {
    }

    protected const REL_MIGRATIONS_PATH = 'migrations';

    public function write(string $migration): void
    {
        $filePath = $this->getAbsMigrationsPath() . '/' . $this->generateFileName($migration);

        file_put_contents($filePath, $migration);

        echo 'ok.';
    }

    protected function getAbsMigrationsPath(): string
    {
        return $this->projectPath . '/' . static::REL_MIGRATIONS_PATH;
    }

    private function generateFileName(string $migration): string
    {
        return date(Migration::MIGRATION_DB_FORMAT) . '_' . hash('md5', $migration) . '.php';
    }
}
