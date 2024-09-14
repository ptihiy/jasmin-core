<?php

namespace Jasmin\Core\Database\Attributes\DataTypes\Integer;

use Attribute;
use Jasmin\Core\Database\Attributes\DataTypes\DataType;

#[Attribute]
class Bigint extends DataType
{
    public function __construct(
        protected bool $unsigned = false,
        protected bool $required = false,
        protected bool $nullable = false,
        protected bool $auto_increment = false
    ) {
    }

    public function getType(): string
    {
        $stmt = "BIGINT";

        if ($this->unsigned) {
            $stmt .= " UNSIGNED";
        }

        if ($this->auto_increment) {
            $stmt .= " AUTO_INCREMENT";
        }

        return $stmt;
    }
}
