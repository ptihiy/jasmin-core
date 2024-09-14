<?php

namespace Jasmin\Core\Jasmin;

use Jasmin\Core\Database\Migrations\MigrationsCreator;

$migrationsCreator = new MigrationsCreator();

$migrationsCreator->run();
