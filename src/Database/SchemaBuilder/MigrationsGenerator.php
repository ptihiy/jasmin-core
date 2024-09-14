<?php

namespace Jasmin\Core\Database\SchemaBuilder;

use InvalidArgumentException;
use Jasmin\Core\Database\ActiveRecord;
use Jasmin\Core\Database\Attributes\Constraints\Constraint;
use Jasmin\Core\Database\Attributes\DataTypes\DataType;
use Jasmin\Core\Database\SchemaBuilder\Imprints\Table;
use ReflectionClass;
use ReflectionProperty;

class MigrationsGenerator
{
    protected const REL_MODELS_DIR_PATH = "/src/Models";

    public function __construct(protected string $dir)
    {
    }

    public function run(): string
    {
        $modelPaths = $this->getModelPaths();

        $absModelsDirPath = $this->getAbsModelsDirPath();

        $stmt = "";

        foreach ($modelPaths as $modelPath) {
            // Get namespace
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

                $stmt .= $this->inspectModel($className);
            }
        }

        return $stmt;
    }

    private function getAbsModelsDirPath(): string
    {
        return $this->dir . self::REL_MODELS_DIR_PATH;
    }

    private function getModelPaths(): ?array
    {
        $absModelsDirPath = $this->getAbsModelsDirPath();

        return array_map(
            fn ($el) => $absModelsDirPath . '/' . $el,
            array_filter(scandir($absModelsDirPath), fn($el) => !is_dir($absModelsDirPath . '/' . $el))
        );
    }

    private function inspectModel(string $className): string
    {
        $reflectionClass = new ReflectionClass($className);

        $tableName = $reflectionClass->getProperty('tableName')->getValue();

        $table = new Table($tableName);

        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $reflectionProperty = new ReflectionProperty($className, $propertyName);

            [$dataTypeAttrs, $constraintAttrs] = [[], []];

            foreach ($reflectionProperty->getAttributes() as $reflectionAttribute) {
                $attr = $reflectionAttribute->newInstance();
                if (is_subclass_of($attr, DataType::class)) {
                    $dataTypeAttrs[] = $attr;
                }
                if (is_subclass_of($attr, Constraint::class)) {
                    $constraintAttrs[] = $attr;
                }
            }

            if (count($dataTypeAttrs) > 1) {
                throw new InvalidArgumentException("One property can only have one column related");
            }
            if (count($dataTypeAttrs) === 1) {
                $table->addColumn(
                    $dataTypeAttrs[0]->getType(),
                    $property->getName(),
                    $dataTypeAttrs[0]->getRequired(),
                    $dataTypeAttrs[0]->getNullable()
                );
            }

            $primaryKeyAttrs = array_filter($constraintAttrs, fn ($e) => $e->getType() == 'PRIMARY KEY');

            if (count($primaryKeyAttrs) > 1) {
                throw new InvalidArgumentException("One property can only have one PK related");
            }
            if (count($primaryKeyAttrs) === 1) {
                $table->addPrimaryKey([$propertyName]);
            }
        }

        return $table->toStmt() . PHP_EOL;
    }
}
