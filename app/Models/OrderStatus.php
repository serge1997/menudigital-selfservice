<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
