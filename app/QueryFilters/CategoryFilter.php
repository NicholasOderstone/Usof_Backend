<?php

namespace App\QueryFilters;

use Kblais\QueryFilter\QueryFilter;

class CategoryFilter extends QueryFilter
{
    public function search(string $value)
    {
        return $this->where('title', 'LIKE', '%' . $value . '%');
    }
}