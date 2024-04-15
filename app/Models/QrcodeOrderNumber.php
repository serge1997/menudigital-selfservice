<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrcodeOrderNumber extends Model
{
    use HasFactory;

    protected $table = "qrcode_orders_numbers";

    protected $fillable = [
        "qrcode_order_number"
    ];
}
