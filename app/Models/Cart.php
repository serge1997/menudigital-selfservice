<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $fillable = [
        'item_id',
        'tableNumber',
        'quantity',
        'unit_price',
        'total'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Menuitems::class, 'item_id');
    }
}
