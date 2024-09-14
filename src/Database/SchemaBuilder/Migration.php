<?php

namespace Jasmin\Core\Database\SchemaBuilder;

use Jasmin\Core\Database\ConnectionManager;
use PDO;
use Jasmin\Core\Reflection\Reflect;

class Migration
{
    public const MIGRATION_DB_FORMAT = "U";

    protected ?PDO $pdo = null;

    public static function create(string $path): Migration
    {
        $className = Reflect::createClassFromFile($path);
        var_dump((new $className())->run());
        //var_dump(Reflect::createClassFromFile($path));
        die();
    }

    public static function since(array $migrations, ?Migration $migration): array
    {


        return [];
    }

    public function __construct()
    {
        $this->pdo = ConnectionManager::getConnection('test');
    }

    public function execute(string $sql)
    {
        $this->pdo->query($sql);
    }
}
