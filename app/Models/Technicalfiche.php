<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technicalfiche extends Model
{
    use HasFactory;

    protected $table = 'technicalfiches';
    protected $fillable = 
        [
            'productID',
            'itemID',
            'quantity'
        ];
}
