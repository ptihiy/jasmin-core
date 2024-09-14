<?php

namespace Jasmin\Core\Database\Attributes\Relationships;

use Attribute;
use Jasmin\Core\Database\QueryBuilder\QueryBuilder;

#[Attribute]
class ManyToManyRelationship extends Relationship
{
    public function __construct(
        public string $leftModel,
        public string $rightModel,
        protected ?string $pivot = null
    ) {
    }

    public function getSql(string $ownerTableName): string
    {
        return "SELECT * FROM tags t 
        RIGHT JOIN news_items_tags_pivot nitp 
        ON t.id = nitp.tag_id 
        WHERE nitp.news_item_id = 63";
    }

    public function getPivotTableName(): string
    {
        if (is_null($this->pivot)) {
            $tableNames = [
                $this->leftModel::getTableName(),
                $this->rightModel::getTableName()
            ];
            sort($tableNames);
            return sprintf("%s_%s_pivot", $tableNames[0], $tableNames[1]);
        } else {
            return $this->pivot;
        }
    }

    public function sync(): void
    {
        //
    }

    public function getGuestTableName(): string
    {
        return $this->model::getTableName();
    }
}
