<?php

namespace Jasmin\Core\Console\Commands;

use Jasmin\Core\Database\SchemaBuilder\MigrationsInstaller;

class InstallMigrationsCommand implements CommandInterface
{
    public function handle(): void
    {
        (new MigrationsInstaller())->run();
    }
}
