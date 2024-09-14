<?php

namespace Jasmin\Core\Database\SchemaBuilder;

use Jasmin\Core\Database\DatabaseResolver;
use Jasmin\Core\Database\SchemaBuilder\Imprints\Table;

class MigrationsInstaller
{
    public function run(): void
    {
        $migrationsTable = new Table('migrations');
        $migrationsTable->addColumn('BIGINT UNSIGNED AUTO_INCREMENT', 'id');
        $migrationsTable->addColumn('TIMESTAMP', 'timestamp');
        $migrationsTable->addPrimaryKey(['id'], 'migrations_pk');
        $migrationsTable->addColumn('VARCHAR(255)', 'migrations');

        // var_dump($migrationsTable->toStmt());
        // die();

        $conn = DatabaseResolver::getConnection();
        $stmt = $conn->prepare($migrationsTable->toStmt());
        $stmt->execute();
    }
}
