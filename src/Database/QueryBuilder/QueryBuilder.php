<?php

namespace Jasmin\Core\Database\QueryBuilder;

use PDO;
use Jasmin\Core\Database\ConnectionManager;

class QueryBuilder implements QueryBuilderInterface
{
    protected string $select = "*";

    protected array $joins = [];

    protected array $wheres = [];

    protected ?string $order = null;

    protected ?int $limit = null;

    public static function table(string $name): QueryBuilderInterface
    {
        return new QueryBuilder($name);
    }

    public function __construct(protected string $table)
    {
        return $this;
    }

    public function select(string $params): QueryBuilderInterface
    {
        return $this;
    }

    public function addJoin(): QueryBuilderInterface
    {
        return $this;
    }

    public function addLeftJoin(
        string $table,
        ?string $owner_id = null,
        ?string $guest_id = null,
        ?string $pivot = null
    ): QueryBuilderInterface {
        $this->joins[] = [
            'type' => 'left',
            'table' => $table,
            'pivot' => $pivot,
            'owner_id' => '',
            'guest_id' => ''
        ];

        return $this;
    }

    public function where(mixed $param, ?string $value = null): QueryBuilderInterface
    {
        if (is_callable($param)) {
            return $param($this);
        }

        $this->wheres[] = [
            'param' => $param,
            'value' => $value
        ];
        return $this;
    }

    public function orWhere(mixed $param, ?string $value = null): QueryBuilderInterface
    {
        if (is_callable($param)) {
            return $param($this);
        }

        $this->wheres[] = [
            'logic' => 'OR',
            'param' => $param,
            'value' => $value
        ];
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
        $this->order = sprintf(
            "ORDER BY %s %s",
            $clause,
            $order
        );
        return $this;
    }

    public function limit(): QueryBuilderInterface
    {
        return $this;
    }

    public function toSql(): string
    {
        $sql = sprintf("SELECT * FROM %s", $this->table);

        foreach ($this->joins as $join) {
            $joinSql = sprintf(" LEFT JOIN news_items_tags_pivot 
            ON news_items.id = news_items_tags_pivot.news_item_id
            LEFT JOIN tags 
            ON news_items_tags_pivot.tag_id = tags.id");
            $sql .= $joinSql;
        }

        return $sql;
    }

    public function get(): array
    {
        $conn = ConnectionManager::getConnection();

        $sql = sprintf("SELECT * FROM %s", $this->table);
        if ($this->wheres) {
            $sql .= " WHERE";
        }
        foreach ($this->wheres as $where) {
            if (isset($where['logic'])) {
                $sql .= ' ' . $where['logic'];
            }
            $sql .= sprintf(" %s = '%s'", $where['param'], $where['value']);
        }

        if ($this->order) {
            $sql .= ' ' . $this->order;
        }

        $stmt = $conn->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
