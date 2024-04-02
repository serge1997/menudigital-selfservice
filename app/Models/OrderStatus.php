<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model
{
    use HasFactory;

    CONST DEBITO = 1;
    CONST CREDITO = 2;
    CONST CASH = 3;
    CONST PIX = 4;
    CONST CANCELAR = 5;
    CONST ANDAMENTO = 6;

    protected $table = 'status';

    public function pedido(): HasMany
    {
        return $this->hasMany(Pedido::class, 'status_id');
    }
}
