<?php

namespace Jasmin\Core\Auth;

use Jasmin\Core\Database\ConnectionManager;
use PDO;
use Jasmin\Core\Database\DatabaseResolver;

class Authenticator implements AuthenticatorInterface
{
    protected static string $loginColumn = 'email';
    protected static string $authTable = 'users';
    protected static ?string $authClass = null;

    public static function setAuthClass(string $authClass): void
    {
        self::$authClass = $authClass;
    }

    public static function login(string $login, string $password): ?AuthenticatibleInterface
    {
        $conn = ConnectionManager::getConnection();

        $stmt = $conn->prepare("SELECT * FROM " . self::$authTable . " WHERE " . self::$loginColumn . " = :login AND password = :password LIMIT 1");
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':password', $password);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $authenticable = new self::$authClass();
            foreach ($stmt->fetch() as $column => $value) {
                if (!in_array($column, self::$authClass::$except)) {
                    $authenticable->{$column} = $value;
                }
            }

            return $authenticable;
        }

        return null;
    }

    public static function authenticateById(int $id): ?AuthenticatibleInterface
    {
        $conn = ConnectionManager::getConnection();

        $stmt = $conn->prepare("SELECT * FROM " . self::$authTable . " WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        if ($stmt->rowCount()) {
            $authenticable = new self::$authClass();
            foreach ($stmt->fetch() as $column => $value) {
                if (!in_array($column, self::$authClass::$except)) {
                    $authenticable->{$column} = $value;
                }
            }

            return $authenticable;
        }

        return null;
    }

    public static function auth(): ?AuthenticatibleInterface
    {
        if (isset($_COOKIE['user_id'])) {
            return self::authenticateById($_COOKIE['user_id']);
        }

        return null;
    }
}
