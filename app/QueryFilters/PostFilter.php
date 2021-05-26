<?php

namespace App\QueryFilters;

use Kblais\QueryFilter\QueryFilter;
use Illuminate\Support\Facades\DB;

class PostFilter extends QueryFilter
{
    public function search(string $value)
    {
        return $this->where('title', 'LIKE', '%' . $value . '%')->orWhere('content', 'LIKE', '%' . $value . '%');
    }

    public function user(string $value)
    {
        $user = DB::table('users')->where('name', $value)->first();
        if ($user) {
            return $this->where('user_id', $user->id);
        }
        
    }

    public function category(string $value)
    {
        $categories = explode(',', $value);
        $category_ids = array();
        try {
            for ($i = 0; $i < count($categories); $i++) {
                $user = DB::table('categories')->where('title', $categories[$i])->first();
                if ($user) {
                    array_push($category_ids, (int)$user->id);
                }
                
            }
        } catch (\ErrorException $e) {
            return $this;
        }
        return DB::table('post_categories')->whereIn('category_id', $category_ids)->get();
    }

    public function startDate($start)
    {
        return $this->where('created_at', '>=', $start);
    }
    public function endDate($end)
    {
        return $this->where('created_at', '<', $end . ' 23:59:59');
    }

    public function order(string $value)
    {
        $sort = explode('$', $value);
        switch ($sort[0]) {
            case 'date':
                return $this->orderBy('created_at', (string)$sort[1]);
        }
    }

    public function page()
    {
        return $this->paginate(10);
    }
}
