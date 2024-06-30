<?php

namespace Jasmin\Core\Database;

use PDO;
use PDOException;

class Connection implements ConnectionInterface
{
    private ?PDO $dbh;

    public function __construct($conn, $host, $db, $user, $password)
    {
        try {
            $this->dbh = new PDO(sprintf("%s:host=%s;dbname=%s", $conn, $host, $db), $user, $password);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }
}
