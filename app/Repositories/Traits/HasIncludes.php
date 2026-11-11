<?php

namespace App\Repositories\Traits;

/**
 * Allow-list quan hệ được eager load qua ?include=; lớp con khai báo property $includes.
 */
trait HasIncludes
{
    private function getIncludeFields(): array
    {
        if (property_exists($this, 'includes')) {
            return $this->includes;
        }

        return [];
    }
}
