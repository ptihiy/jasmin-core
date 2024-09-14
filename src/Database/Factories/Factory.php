<?php

namespace Jasmin\Core\Database\Factories;

use InvalidArgumentException;
use ReflectionClass;
use Jasmin\Core\Database\DatabaseResolver;

abstract class Factory
{
    protected static ?Factory $instance = null;
    protected static ?string $model = null;
    protected array $items = [];

    private function __construct()
    {
    }

    private static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }


    public static function make(int $count): Factory
    {
        $items = [];

        for ($i = 1; $i <= $count; $i++) {
            $items[] = static::create();
        }

        $instance = static::getInstance();

        $instance->items = $items;

        return $instance;
    }

    public function save(): void
    {
        $conn = DatabaseResolver::getConnection();

        $tableName = $this->resolveTable();

        if (!count($this->items)) {
            return;
        }

        $columns = array_keys($this->items[0]);

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s);",
            $tableName,
            implode(', ', $columns),
            implode(', ', array_map(fn ($s) => ':' . $s, $columns))
        );

        $stmt = $conn->prepare($sql);
        foreach ($this->items as $item) {
            var_dump($item);
            foreach ($columns as $column) {
                $stmt->bindValue($column, $item[$column]);
            }
            $stmt->execute();
        }

        var_dump($sql);
    }

    private function resolveTable(): string
    {
        if (!is_null(static::$model)) {
            $modelName = static::$model;
        } else {
            $className = (new ReflectionClass($this))->getShortName();

            $modelName = substr($className, 0, -7);
        }

        $modelPath = project_path() . '/src/Models/' . $modelName . '.php';

        if (!file_exists($modelPath)) {
            throw new InvalidArgumentException("Model not found.");
        } else {
            $handle = fopen($modelPath, "r");

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

                include $modelPath;

                $className = $ns . '\\' . basename($modelPath, '.php');

                $reflectionClass = new ReflectionClass($className);

                $tableName = $reflectionClass->getProperty('tableName')->getValue();

                return $tableName;
            }
        }
    }



    abstract public static function create();
}
