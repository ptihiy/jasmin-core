<?php

namespace Jasmin\Core\Validation\Rules;

use PDO;
use Jasmin\Core\Database\DatabaseResolver;

class Unique implements RuleInterface
{
    public static function validate(mixed $value, array $data, ?string $extra = null): bool
    {
        list($table, $column) = explode(',', $extra);
        $conn = (DatabaseResolver::getConnection())->getConnection();
        $stmt = $conn->prepare("SELECT * FROM $table WHERE $column = :value LIMIT 1");
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->rowCount() === 0;
    }

    public static function getMessage(): string
    {
        return "The field must be unique";
    }
}
