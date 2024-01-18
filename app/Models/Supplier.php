<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $fillable =
        [
            'sup_name',
            'sup_personID',
            'sup_tel',
            'sup_city',
            'sup_neighborhood',
            'sup_email'
        ];

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'prod_supplierID');
    }
}
