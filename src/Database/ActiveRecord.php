<?php

namespace Jasmin\Core\Database;

use PDO;
use ReflectionClass;
use ReflectionProperty;
use InvalidArgumentException;
use Jasmin\Core\Database\Attributes\Required;
use Jasmin\Core\Database\ConnectionInterface;
use Jasmin\Core\Database\Attributes\DataTypes\DataType;

abstract class ActiveRecord
{
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

    public function all()
    {
        $stmt = $this->conn->getConnection()->prepare("SELECT * FROM $this->tableName ORDER BY id DESC LIMIT 10");
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
                if (DataType::class === $reflectionAttribute->getName()) {
                    $data[$reflectionProperty->getName()] = $this->{$reflectionProperty->getName()};
                }
            }
        }

        $values = implode(', ', array_keys($data));
        $bindings = implode(', ', array_map(fn ($k) => ':' . $k, array_keys($data)));
        $prepare = "INSERT INTO $this->tableName (" . $values . ") VALUES (" . $bindings . ")";

        $stmt = $this->conn->getConnection()->prepare($prepare);
        foreach ($data as $prop => $value) {
            $stmt->bindParam(':' . $prop, $value);
        }
        $stmt->execute();
    }
}
