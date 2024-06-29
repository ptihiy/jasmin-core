<?php

namespace Jasmin\Core\Database;

use PDO;
use PDOException;

class Connection
{
    private ?PDO $dbh;

    public function __construct($host, $db, $user, $password)
    {
        try {
            $this->dbh = new PDO(sprintf("mysql:host=%s;dbname=%s", $host, $db), $user, $password);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}
