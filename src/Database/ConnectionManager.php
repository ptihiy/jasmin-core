<?php

namespace Jasmin\Core\Database;

use InvalidArgumentException;

class ConnectionManager
{
    protected static array $connections = [];

    public static function getConnection(string $name = "default")
    {
        if (!array_key_exists($name, static::$connections)) {
            throw new InvalidArgumentException(sprintf("Connection %s does not exist", $name));
        }

        return (static::$connections[$name])->getConnection();
    }

    public static function addConnection(Connection $connection, string $name = "default")
    {
        static::$connections[$name] = $connection;
    }
}
