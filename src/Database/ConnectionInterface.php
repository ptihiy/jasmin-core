<?php

namespace Jasmin\Core\Database;

use PDO;

interface ConnectionInterface
{
    public function getConnection(): PDO;
}
