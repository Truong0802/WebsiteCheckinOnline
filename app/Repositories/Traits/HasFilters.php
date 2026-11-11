<?php

namespace App\Repositories\Traits;

/**
 * Allow-list filter: mặc định created_at, updated_at; lớp con bổ sung qua canFilters().
 */
trait HasFilters
{
    private function getAllowFilters(): array
    {
        $allowFilter = [
            'created_at',
            'updated_at',
        ];

        if (method_exists($this, 'canFilters')) {
            $allowFilter = array_merge($allowFilter, $this->canFilters());
        }

        return $allowFilter;
    }
}
