<?php

namespace Jasmin\Core\Database;

class DatabaseResolver
{
    private static $conn;

    public static function getConnection()
    {
        return self::$conn;
    }

    public static function setConnection($conn)
    {
        self::$conn = $conn;
    }
}
