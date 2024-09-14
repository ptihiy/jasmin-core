<?php

namespace Jasmin\Core\Database\Attributes\DataTypes;

use Attribute;

#[Attribute]
class Text extends DataType
{
    public function __construct(
        protected bool $required = false,
        protected bool $nullable = true
    ) {
    }

    public function getType(): string
    {
        return 'TEXT';
    }
}
