<?php
namespace App\Main\PurchaseRequisition;

interface PurchaseRequisitionRepositoryInterface
{
    public function deleteByRequisitionIdProductId($requisition_id, $product_id);
    public function deleteByRequisitionId($requisition_id);
}
