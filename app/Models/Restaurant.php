<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    CONST RESTAURANT_KEY = 1;
    use HasFactory;

    protected $table = 'restaurant';
    protected $fillable = [
        'name',
        'email',
        'city',
        'neighborhoods',
        'logo',
        'open_hour',
        'close_hour'
    ];
}
