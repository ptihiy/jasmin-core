<?php

namespace Jasmin\Core\Database\Attributes\DataTypes;

use Attribute;

#[Attribute]
class Varchar extends DataType
{
    public function __construct(
        protected int $length = 255,
        protected bool $required = false,
        protected bool $nullable = false
    ) {
    }

    public function getType(): string
    {
        return 'VARCHAR(' . $this->length . ')';
    }
}
