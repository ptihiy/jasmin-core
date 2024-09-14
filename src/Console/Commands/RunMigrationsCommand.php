<?php

namespace Jasmin\Core\Console\Commands;

use Jasmin\Core\Database\SchemaBuilder\MigrationsRunner;

class RunMigrationsCommand implements CommandInterface
{
    public function handle(): void
    {
        $runner = new MigrationsRunner(project_path());

        $runner->run();

        echo "Migrations were handled.";
    }
}
