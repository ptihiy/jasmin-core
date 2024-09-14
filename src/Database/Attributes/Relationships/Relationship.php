<?php

namespace Jasmin\Core\Database\Attributes\Relationships;

use Attribute;

#[Attribute]
abstract class Relationship
{
    protected string $model;

    abstract public function getSql(string $ownerTableName);

    public function getModel()
    {
        return $this->model;
    }
}
