<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequisition extends Model
{
    use HasFactory;

    CONST REQUISITION_WAITING = 1;
    CONST REQUISITION_APPROVED = 2;
    CONST REQUISITION_REJECTED = 3;

    protected $table = 'purchase_requisitions';
    protected $fillable = [
        'user_id',
        'status_id',
        'department_id',
        'delivery_date',
        'observation'
    ];

    public function stockentry(): HasMany
    {
        return $this->hasMany(StockEntry::class, 'requisition_id');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(RequisitionItem::class, 'requisition_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
