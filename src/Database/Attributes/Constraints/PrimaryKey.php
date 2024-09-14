<?php

namespace Jasmin\Core\Database\Attributes\Constraints;

use Attribute;

#[Attribute]
class PrimaryKey extends Constraint
{
    public function getType(): string
    {
        return 'PRIMARY KEY';
    }
}
