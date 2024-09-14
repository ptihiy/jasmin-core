<?php

namespace Jasmin\Core\Console\Commands;

use Jasmin\Core\Database\DatabaseResolver;
use Jasmin\Core\Database\SchemaBuilder\MigrationsWriter;
use Jasmin\Core\Database\SchemaBuilder\MigrationsGenerator;

class GenerateMigrationsCommand implements CommandInterface
{
    public function __construct(protected ?array $options)
    {
    }

    public function handle(): void
    {
        $generator = new MigrationsGenerator(project_path());

        $migrations = $generator->run();

        $writer = new MigrationsWriter(project_path());

        $writer->write($migrations);

        // $conn = DatabaseResolver::getConnection();
        // $stmt = $conn->prepare($migrations);
        // $stmt->execute();

        echo "Migrations were prepared.";
    }
}
