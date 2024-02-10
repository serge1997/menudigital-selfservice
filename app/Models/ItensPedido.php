<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItensPedido extends Model
{
    use HasFactory;

    protected $table = 'itens_pedido';

    protected $fillable = [
        'item_emissao',
        'item_pedido',
        'item_id',
        'item_quantidade',
        'item_price',
        'item_total',
        'item_option',
        'item_comments'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'item_pedido');
    }

    public function menuitem(): BelongsTo
    {
        return $this->belongsTo(Menuitems::class, 'item_id');
    }
}
