<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kblais\QueryFilter\Filterable;
class Category extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    use Filterable;
    
    protected $fillable = [
        'title',
        'description'
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
