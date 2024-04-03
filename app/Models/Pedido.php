<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $filable = [
        'ped_emissao',
        'ped_tableNumber',
        'ped_customerName',
        'ped_delete',
        'status_id',
        'user_id',
        'ped_customer_quantity'
    ];

    public function item(): HasMany
    {
        return $this->hasMany(ItensPedido::class, 'item_pedido');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function tablenumber(): BelongsTo
    {
        return $this->belongsTo(TableNumber::class, 'ped_tableNumber');
    }
}
