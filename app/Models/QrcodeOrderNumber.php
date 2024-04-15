<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrcodeOrderNumber extends Model
{
    use HasFactory;

    protected $table = "qrcode_orders_numbers";

    protected $fillable = [
        "qrcode_order_number"
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Pedido::class, 'qrcode_order_number_id');
    }
}
