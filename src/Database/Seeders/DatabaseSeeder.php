<?php

namespace Jasmin\Core\Database\Seeders;

use ReflectionClass;

class DatabaseSeeder
{
    protected const REL_SEEDERS_DIR_PATH = "/src/Database";

    public function __construct(protected string $dir)
    {
    }

    public function run(): void
    {
        $availableSeeders = $this->getSeederPaths();

        // Run main seeder
        if (count(array_filter($availableSeeders, fn ($e) => basename($e, '.php') === 'DatabaseSeeder'))) {
            $this->runMainSeeder();
        } else {
            echo 'DatabaseSeeder not found. Finishing job.';
        }
    }

    protected function getAbsSeedersDirPath(): string
    {
        return $this->dir . self::REL_SEEDERS_DIR_PATH;
    }

    protected function getSeederPaths(): array
    {
        $absSeedersDirPath = $this->getAbsSeedersDirPath();

        return array_map(
            fn ($el) => $absSeedersDirPath . '/' . $el,
            array_filter(scandir($absSeedersDirPath), fn($el) => !is_dir($absSeedersDirPath . '/' . $el))
        );
    }

    protected function runMainSeeder(): void
    {
        $mainSeederPath = $this->getAbsSeedersDirPath() . '/DatabaseSeeder.php';

        $handle = fopen($mainSeederPath, "r");

        $ns = "";

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'namespace') === 0) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);

            include $mainSeederPath;

            $className = $ns . '\\' . basename($mainSeederPath, '.php');

            $reflectionClass = new ReflectionClass($className);
            $seeder = $reflectionClass->newInstance();
            $seeder->run();
        }
    }
}
