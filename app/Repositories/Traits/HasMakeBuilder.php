<?php

namespace App\Repositories\Traits;

use Spatie\QueryBuilder\QueryBuilder;

/**
 * Tạo QueryBuilder (spatie) cho model của repository và allow-list ?fields[]=.
 */
trait HasMakeBuilder
{
    private function getFields(): array
    {
        if (property_exists($this, 'fields')) {
            return $this->fields;
        }

        return [];
    }

    public function makeQueryBuilder(): QueryBuilder
    {
        return QueryBuilder::for($this->getModel());
    }
}
