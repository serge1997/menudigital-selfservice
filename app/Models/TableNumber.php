<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TableNumber extends Model
{
    use HasFactory;

    protected $table = 'tablenumber';

    public function pedido(): HasMany
    {
        return $this->hasMany(Pedido::class, 'ped_tableNumber');
    }
}
