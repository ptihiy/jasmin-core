<?php

namespace Jasmin\Core\Console;

use Jasmin\Core\Database\Connection;
use Jasmin\Core\Database\DatabaseResolver;
use Jasmin\Core\Kernel\Kernel as BaseKernel;
use Jasmin\Core\Console\Commands\ShowHelpCommand;
use Jasmin\Core\Console\Commands\ClearCacheCommand;
use Jasmin\Core\Console\Commands\SeedDatabaseCommand;
use Jasmin\Core\Console\Commands\RunMigrationsCommand;
use Jasmin\Core\Console\Commands\InstallMigrationsCommand;
use Jasmin\Core\Console\Commands\GenerateMigrationsCommand;
use Jasmin\Core\Database\ConnectionManager;

class Kernel extends BaseKernel
{
    public function __construct(string $dir)
    {
        parent::__construct($dir);

        $this->bootstrap();
    }

    private ?array $commands = [
        // help
        'h' => ShowHelpCommand::class,
        // clear cache
        'c:c' => ClearCacheCommand::class,
        // database migrations migrate
        'd:m:m' => RunMigrationsCommand::class,
        // database migrations install
        'd:m:i' => InstallMigrationsCommand::class,
        // database migrations generate
        'd:m:g' => GenerateMigrationsCommand::class
    ];

    public function start(): void
    {
        $arguments = array_slice((array) $_SERVER['argv'], 1);
        if (!count($arguments)) {
            echo 'no arguments given';
            die();
        }

        $commandArg = $_SERVER['argv'][1];
        $optionArgs = array_slice((array) $_SERVER['argv'], 2);

        if (array_key_exists($commandArg, $this->commands)) {
            (new $this->commands[$commandArg]($optionArgs))->handle();
            die();
        } else {
            echo 'wrong argument given';
            die();
        }
    }

    private function bootstrap()
    {
        foreach ($this->getConfig('database') as $cName => $c) {
            ConnectionManager::addConnection(
                new Connection(...$c),
                $cName
            );
        }
    }
}
