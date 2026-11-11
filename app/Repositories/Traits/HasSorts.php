<?php

namespace App\Repositories\Traits;

/**
 * Sort mặc định: property $default_sort nếu có, ngược lại -{table}.updated_at (mới nhất trước).
 * Allow-list sort: created_at, updated_at + canSorts() của lớp con.
 */
trait HasSorts
{
    private function getDefaultSort(): string
    {
        if (property_exists($this, 'default_sort')) {
            return $this->default_sort;
        }

        return '-'.$this->_model->getTable().'.updated_at';
    }

    private function getAllowSorts(): array
    {
        $allowSorts = [
            'created_at',
            'updated_at',
        ];

        if (method_exists($this, 'canSorts')) {
            $allowSorts = array_merge($allowSorts, $this->canSorts());
        }

        return $allowSorts;
    }
}
