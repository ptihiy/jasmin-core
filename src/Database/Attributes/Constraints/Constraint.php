<?php

namespace Jasmin\Core\Database\Attributes\Constraints;

use Attribute;

#[Attribute]
abstract class Constraint
{
    abstract public function getType(): string;
}
