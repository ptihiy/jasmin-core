<?php

namespace Jasmin\Core\Database;

use InvalidArgumentException;
use Jasmin\Core\Database\Attributes\DataTypes\DataType;
use Jasmin\Core\Database\Attributes\Required;
use ReflectionClass;
use Jasmin\Core\Database\ConnectionInterface;
use ReflectionProperty;

class ActiveRecord
{
    private $conn;
    protected string $tableName;

    public function __construct()
    {
        $this->conn = DatabaseResolver::getConnection();
    }

    public function all()
    {
        $stmt = $this->conn->getConnection()->prepare("SELECT * FROM $this->tableName");
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
