<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockEntry extends Model
{
    use HasFactory;

    protected $table = 'stock_entries';
    protected $fillable =
        [
            'productID',
            'supplierID',
            'requisition_id',
            'unitCost',
            'quantity',
            'totalCost'
        ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'productID');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplierID');
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class, 'requisition_id');
    }
}
