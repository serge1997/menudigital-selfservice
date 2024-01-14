<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    //public int $productID;
    protected $table = 'saldos';
    public $timestamps = false;
    protected $fillable =
        [
            'productID',
            'saldoInicial',
            'saldoFinal',
            'emissao'
        ];
}
