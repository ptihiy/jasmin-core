<?php

namespace Jasmin\Core\Database;

use PDO;
use Iterator;
use ArrayAccess;
use Jasmin\Core\Database\QueryBuilder\QueryBuilder;
use Jasmin\Core\Database\Attributes\Relationships\ManyToManyRelationship;

class LazyCollection implements ArrayAccess, Iterator
{
    protected array $items = [];

    protected int $position = 0;

    protected bool $requested = false;

    public function __construct(protected int $ownerId, protected ManyToManyRelationship $relationship)
    {
    }

    public function getItems()
    {
        if (!$this->requested) {
            // query for the items
            $conn = ConnectionManager::getConnection();

            $stmt = $conn->prepare(
                sprintf(
                    'SELECT * FROM %1$s AS rt 
                    RIGHT JOIN %2$s as pt
                    ON rt.id = pt.%3$s 
                    WHERE pt.%4$s = :id',
                    $this->relationship->rightModel::getTableName(),
                    $this->relationship->getPivotTableName(),
                    $this->relationship->rightModel::getTableId(),
                    $this->relationship->leftModel::getTableId()
                )
            );

            $stmt->bindValue(':id', $this->ownerId);

            $stmt->execute();

            $this->items = $stmt->fetchAll(PDO::FETCH_CLASS, $this->relationship->rightModel);

            $this->requested = true;
        }

        return $this->items;
    }

    public function sync(array $itemIds)
    {
        $conn = ConnectionManager::getConnection();

        $stmt = $conn->prepare(
            sprintf(
                'DELETE FROM %1$s AS pt WHERE pt.%2$s = :id',
                $this->relationship->getPivotTableName(),
                $this->relationship->rightModel::getTableId()
            )
        );

        $stmt->bindValue(':id', $this->ownerId);

        $stmt->execute();

        $stmt = $conn->prepare(
            sprintf(
                'INSERT INTO %1$s (%2$s, %3$s) VALUES (:leftModelId, :rightModelId)',
                $this->relationship->getPivotTableName(),
                $this->relationship->leftModel::getTableId(),
                $this->relationship->rightModel::getTableId()
            )
        );

        foreach ($itemIds as $itemId) {
            $stmt->bindValue(':leftModelId', $this->ownerId);
            $stmt->bindValue(':rightModelId', $itemId);
            $stmt->execute();
        }
    }

    public function toArray(): array
    {
        return $this->getItems();
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->getItems()[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->getItems()[$offset]) ? $this->getItems()[$offset] : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->getItems()[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->getItems()[$offset]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): mixed
    {
        return $this->getItems()[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->getItems()[$this->position]);
    }
}
