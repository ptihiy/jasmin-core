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

abstract class ActiveRecord implements ArrayAccess
{
    use ArrayAccessable;

    private $conn;
    protected static string $tableName;

    public function __construct()
    {
        $this->conn = DatabaseResolver::getConnection();
    }

    public static function get()
    {
        $conn = (DatabaseResolver::getConnection())->getConnection();
        $tableName = static::$tableName;
        $stmt = $conn->prepare("SELECT * FROM $tableName ORDER BY id DESC LIMIT 10");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function find(int $id)
    {
        $conn = (DatabaseResolver::getConnection())->getConnection();
        $tableName = static::$tableName;
        $stmt = $conn->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $stmt->fetch();
    }

    public static function all()
    {
        $conn = (DatabaseResolver::getConnection())->getConnection();
        $stmt = $conn->prepare("SELECT *  " . static::$tableName . " ORDER BY id DESC LIMIT 10");
        $stmt->execute();
        return $stmt;
    }

    public function save()
    {
        $data = [];
        $reflectionClass = new ReflectionClass(static::class);
        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            foreach ($reflectionProperty->getAttributes() as $reflectionAttribute) {
                if (Required::class === $reflectionAttribute->getName() && empty($this->{$reflectionProperty->getName()})) {
                    throw new InvalidArgumentException("Not all data is provided");
                }
                if ($reflectionProperty->isInitialized($this) && DataType::class === $reflectionAttribute->getName()) {
                    if (!is_null($this->{$reflectionProperty->getName()})) {
                        $data[$reflectionProperty->getName()] = $this->{$reflectionProperty->getName()};
                    }
                }
            }
        }

        $values = implode(', ', array_keys($data));
        $bindings = implode(', ', array_map(fn ($k) => ':' . $k, array_keys($data)));
        $prepare = "INSERT INTO " . static::$tableName . " (" . $values . ") VALUES (" . $bindings . ")";

        $stmt = $this->conn->getConnection()->prepare($prepare);
        foreach ($data as $prop => $value) {
            $stmt->bindValue(':' . $prop, $value);
        }

        $stmt->execute();
    }
}
