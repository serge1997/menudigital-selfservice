<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';
    protected $fillable = [
        'product_id',
        'item_id',
        'user_id',
        'quantity',
        'observation'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Menuitems::class, 'item_id');
    }
}
