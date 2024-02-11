<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
            'prod_unmed',
            'min_quantity'
        ];
    public  function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'prod_supplierID');
    }

    public function stockentry(): HasMany
    {
        return $this->hasMany(StockEntry::class, 'productID');
    }

    public function saldo(): BelongsTo
    {
        return $this->belongsTo(Saldo::class, 'productID');
    }
}
