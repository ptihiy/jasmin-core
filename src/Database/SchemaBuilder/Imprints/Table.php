<?php

namespace Jasmin\Core\Database\SchemaBuilder\Imprints;

use Jasmin\Core\Database\QueryBuilder\QueryBuilder;
use Jasmin\Core\Database\QueryBuilder\QueryBuilderInterface;
use Jasmin\Core\Database\SchemaBuilder\DataTypes\DataType;

class Table implements QueryBuilderInterface
{
    private array $columns = [];
    private array $constraints = [];
    private array $primaryKey = [];

    public function __construct(protected string $tableName)
    {
    }

    public function addColumn(
        string $type,
        string $name,
        bool $required = false,
        bool $nullable = false,
        mixed $default = null
    ) {
        $this->columns[$name] = [
            'type' => $type,
            'required' => $required,
            'nullable' => $nullable,
            'default' => $default
        ];
    }

    public function addConstraint(string $type, string $name, array $target)
    {
        $this->constraints[$name] = [
            'type' => $type
        ];
    }

    public function addPrimaryKey(array $targets, string $name = null)
    {
        $this->primaryKey = [
            'name' => $name,
            'targets' => array_merge($this->primaryKey['targets'] ?? [], $targets)
        ];
    }

    public function toStmt()
    {
        $stmt = 'CREATE TABLE ' . $this->tableName;
        $stmt .= ' (';
        $columnStmts = [];
        foreach ($this->columns as $name => $data) {
            $columnStmt = $name . ' ' . $data['type'];

            if (!$data['nullable']) {
                $columnStmt .= ' NOT NULL';
            }

            $columnStmts[] = $columnStmt;
        }

        // Primary key
        if ($this->primaryKey) {
            $columnStmts[] = $this->generatePkStmt();
        }

        $stmt .= implode(', ', $columnStmts);

        $stmt .= ');';
        return $stmt;
    }

    protected function generatePkStmt(): string
    {
        return sprintf(
            'CONSTRAINT %s PRIMARY KEY (%s)',
            $this->primaryKey['name'] ?? 'pk_' . $this->tableName,
            implode(', ', $this->primaryKey['targets'])
        );
    }

    public function select(string $params): QueryBuilderInterface
    {
        $queryBuilder = new QueryBuilder($this->tableName);
        return $queryBuilder;
    }

    public function query(): QueryBuilderInterface
    {
        return $this;
    }

    public function where(mixed $param, ?string $value = null): QueryBuilderInterface
    {
        return $this;
    }

    public function orWhere(mixed $param, ?string $value = null): QueryBuilderInterface
    {
        return $this;
    }

    public function groupBy(): QueryBuilderInterface
    {
        return $this;
    }

    public function having(): QueryBuilderInterface
    {
        return $this;
    }

    public function orderBy(string $clause, string $order): QueryBuilderInterface
    {
        return $this;
    }

    public function limit(): QueryBuilderInterface
    {
        return $this;
    }
}
