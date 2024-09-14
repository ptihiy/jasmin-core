<?php

namespace Jasmin\Core\Database\Attributes\Constraints;

use Attribute;

#[Attribute]
class ForeignKey extends Constraint
{
    public function getType(): string
    {
        return 'FOREIGN KEY';
    }
}
