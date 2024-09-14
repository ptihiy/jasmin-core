<?php

namespace Jasmin\Core\Console\Commands;

use Jasmin\Core\Database\Seeders\DatabaseSeeder;

class SeedDatabaseCommand implements CommandInterface
{
    public function __construct(protected string $dir)
    {
    }

    public function handle(): void
    {
        $seeder = new DatabaseSeeder($this->dir);

        $seeder->run();
    }
}
