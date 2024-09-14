<?php

namespace Jasmin\Core\Database\SchemaBuilder\DataTypes;

abstract class DataType
{
    protected bool $required = false;
    protected bool $nullable = false;

    public function __construct(protected string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
