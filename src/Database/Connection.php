<?php

namespace Jasmin\Core\Database;

use PDO;
use PDOException;

class Connection implements ConnectionInterface
{
    private ?PDO $dbh;

    public function __construct($conn, $host, $port, $db, $user, $pass)
    {
        try {
            $this->dbh = new PDO(sprintf("%s:host=%s;port=%s;dbname=%s", $conn, $host, $port, $db), $user, $pass);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }
}
