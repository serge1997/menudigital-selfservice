<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TableNumber extends Model
{
    use HasFactory;

    protected $table = 'tablenumber';

    public function pedido(): HasOne
    {
        return $this->hasOne(Pedido::class, 'ped_tableNumber');
    }
}
