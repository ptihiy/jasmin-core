<?php

namespace Jasmin\Core\Database;

use PDO;
use ArrayAccess;
use ReflectionClass;
use ReflectionProperty;
use InvalidArgumentException;
use Jasmin\Core\Traits\ArrayAccessable;
use Jasmin\Core\Database\Attributes\Required;
use Jasmin\Core\Database\ConnectionInterface;
use Jasmin\Core\Database\Attributes\DataTypes\DataType;
use Jasmin\Core\Database\Attributes\Relationships\Relationship;
use Jasmin\Core\Database\QueryBuilder\QueryBuilder;
use Jasmin\Core\Database\QueryBuilder\QueryBuilderInterface;

abstract class ActiveRecord extends QueryBuilder implements ArrayAccess, ActiveRecordInterface
{
    use ArrayAccessable;

    private $conn;
    protected static string $tableName;
    protected ?QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->conn = ConnectionManager::getConnection();
    }

    public static function __callStatic($name, $arguments): QueryBuilder
    {
        $activeRecord = static::table(static::$tableName);
        return $activeRecord->{$name}(...$arguments);
    }

    public static function query(): QueryBuilder
    {
        return static::table(static::$tableName);
    }

    public static function getTableName(): string
    {
        return static::$tableName;
    }

    public static function getTableId(): string
    {
        return preg_replace('~(s$)~', '', static::$tableName) . '_id';
    }

    public function get(): array
    {
        $conn = ConnectionManager::getConnection();
        $tableName = static::$tableName;

        $queryBuilder = new QueryBuilder(static::getTableName());

        // Scan model for relationships
        $relationships = static::getRelationships();


        // foreach ($relationships as $relationship) {
        //     $queryBuilder->addLeftJoin(
        //         $relationship->getGuestTableName()
        //     );
        //     //$stmt .= $relationship->getSql(static::$tableName);
        // }

        //var_dump($queryBuilder->toSql());

        $stmt = $conn->prepare($queryBuilder->toSql());

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function find(mixed $expr)
    {
        if (is_int($id = $expr)) {
            $conn = ConnectionManager::getConnection();
            $stmt = $conn->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
            $obj = $stmt->fetch();
            $obj->initCollections();
            return $obj ?: null;
        } elseif (is_array($expr)) {
            $qb = new QueryBuilder(static::$tableName);
            foreach ($expr as $param => $value) {
                $qb->where($param, $value);
            }

            $items = $qb->get();
            if (count($items)) {
                $model = new static();
                foreach ($items[0] as $prop => $value) {
                    $model->{$prop} = $value;
                }
                $model->initCollections();
                return $model;
            }
            return null;
            // $conn = ConnectionManager::getConnection();
            // $stmt = $conn->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id LIMIT 1");
            // $stmt->bindValue(':id', $id);
            // $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
            // $obj = $stmt->fetch();
            // $obj->initCollections();
            // return $obj ?: null;
        }
    }

    public static function all(): array
    {
        $activeRecord = static::table(static::$tableName);
        return $activeRecord->get();
    }

    private static function getRelationships(): array
    {
        $relationships = [];

        $reflectionClass = new ReflectionClass(static::class);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            foreach ($reflectionProperty->getAttributes() as $reflectionAttribute) {
                $attr = $reflectionAttribute->newInstance();
                if (is_subclass_of($attr, Relationship::class)) {
                    $relationships[] = $attr;
                }
            }
        }

        return $relationships;
    }

    public function save()
    {
        $data = [];
        $reflectionClass = new ReflectionClass(static::class);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            foreach ($reflectionProperty->getAttributes() as $reflectionAttribute) {
                if (Required::class === $reflectionAttribute->getName() && empty($this->{$reflectionProperty->getName()})) {
                    throw new InvalidArgumentException("Field " . $reflectionProperty->getName() . " is required");
                }
                if (
                    $reflectionProperty->isInitialized($this)
                    && is_subclass_of($reflectionAttribute->getName(), DataType::class)
                ) {
                    if (!is_null($this->{$reflectionProperty->getName()})) {
                        $data[$reflectionProperty->getName()] = $this->{$reflectionProperty->getName()};
                    }
                }
            }
        }

        $values = implode(', ', array_keys($data));
        $bindings = implode(', ', array_map(fn ($k) => ':' . $k, array_keys($data)));
        $prepare = "INSERT INTO " . static::$tableName . " (" . $values . ") VALUES (" . $bindings . ")";

        $conn = ConnectionManager::getConnection();

        $stmt = $conn->prepare($prepare);

        foreach ($data as $prop => $value) {
            $stmt->bindValue(':' . $prop, $value);
        }

        $stmt->execute();

        // If the record was not synced before we need to update id
        if ($id = $conn->lastInsertId()) {
            $this->id = $id;
        }

        $this->initCollections();
    }

    public static function delete(int $id)
    {
        $conn = ConnectionManager::getConnection();
        $stmt = $conn->prepare("DELETE FROM " . static::$tableName . " WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    public function __set(string $name, string $value)
    {
    }

    public function __get(string $name)
    {
    }

    public function initCollections()
    {
        $reflectionClass = new ReflectionClass(static::class);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            if (is_a($reflectionProperty->getType()->getName(), LazyCollection::class, true)) {
                $this->{$reflectionProperty->getName()} = new LazyCollection(
                    $this->id,
                    $reflectionProperty->getAttributes()[0]->newInstance()
                );
            }
        }
    }
}
