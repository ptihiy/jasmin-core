<?php

namespace Jasmin\Core\Database;

use Jasmin\Core\Database\ConnectionInterface;

class ActiveRecord
{
    protected string $tableName;

    public function __construct(private ConnectionInterface $conn)
    {
    }

    public function all()
    {
        $stmt = $this->conn->getConnection()->prepare("SELECT * FROM $this->tableName");
        $stmt->execute();
        return $stmt;
    }

    public function save()
    {
        $stmt = $this->conn->getConnection()->prepare("INSERT INTO news_items (title, text) VALUES (:title, :text)");
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':text', $this->text);
        $stmt->execute();
    }
}
