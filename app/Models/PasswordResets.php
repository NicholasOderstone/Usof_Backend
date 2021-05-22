<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResets extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'email',
        'token'
    ];

    protected $casts = [
        'email' => 'string',
        'token' => 'string'
    ];
}