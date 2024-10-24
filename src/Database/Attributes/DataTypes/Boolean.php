<?php

namespace Jasmin\Core\Database\Attributes\DataTypes;

use Attribute;

#[Attribute]
class Boolean extends DataType
{
    public function __construct(
        protected bool $required = true,
        protected bool $nullable = false
    ) {
    }

    public function getType(): string
    {
        return 'BOOLEAN';
    }
}
