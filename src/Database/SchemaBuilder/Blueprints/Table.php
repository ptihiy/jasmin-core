<?php

namespace Jasmin\Core\Database\SchemaBuilder\Blueprints;

class Table
{
    public function generate(): string
    {
        return "CREATE TABLE person
        (
            id SMALLINT UNSIGNED,
            fname VARCHAR(20),
            CONSTRAINT pk_person PRIMARY KEY (id)
        );";
    }
}
