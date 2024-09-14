<?php

namespace Jasmin\Core\Database\Attributes\DataTypes;

use Attribute;

#[Attribute]
abstract class DataType
{
    protected bool $required = false;
    protected bool $nullable = false;

    abstract public function getType(): string;

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function getNullable(): bool
    {
        return $this->nullable;
    }
}
