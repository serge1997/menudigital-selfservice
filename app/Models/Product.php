<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $fillable =
        [
            'prod_name',
            'prod_desc',
            'prod_contain',
            'prod_supplierID',
            'prod_unmed'
        ];      
}
