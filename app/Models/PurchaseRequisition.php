<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    use HasFactory;

    protected $table = 'purchase_requisitions';
    protected $fillable = [
        'user_id',
        'status_id',
        'department_id',
        'delivery_date'
    ];
}
