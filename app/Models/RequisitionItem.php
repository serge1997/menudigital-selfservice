<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    use HasFactory;

    protected $table = 'itens_requisitions';
    protected $fillable = [
        'requisition_id',
        'product_id',
        'status_id',
        'quantity',
        'cost',
        'total'
    ];
}
