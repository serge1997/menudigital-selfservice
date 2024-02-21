<?php
namespace App\Main\PurchaseRequisition;
use App\Models\RequisitionItem;
use App\Models\PurchaseRequisition;
class PurchaseRequisitionRepository implements PurchaseRequisitionRepositoryInterface
{
    public function deleteByRequisitionIdProductId($requisition_id, $product_id): void
    {
        RequisitionItem::where([
            ['requisition_id', $requisition_id],
            ['product_id', $product_id]
        ])->update([
            'is_delete' => true
        ]);
    }
    public function deleteByRequisitionId($requisition_id): void
    {
        PurchaseRequisition::where('id', $requisition_id)
            ->update([
                'is_delete' => true
            ]);
    }
}
