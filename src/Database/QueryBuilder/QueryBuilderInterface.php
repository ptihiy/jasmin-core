<?php

namespace Jasmin\Core\Database\QueryBuilder;

interface QueryBuilderInterface
{
    public function select(string $params): QueryBuilderInterface;

    public function where(mixed $param, ?string $value = null): QueryBuilderInterface;

    public function orWhere(mixed $param, ?string $value = null): QueryBuilderInterface;

    public function groupBy(): QueryBuilderInterface;

    public function having(): QueryBuilderInterface;

    public function orderBy(string $clause, string $order): QueryBuilderInterface;

    public function limit(): QueryBuilderInterface;

    public function get(): array;
}
