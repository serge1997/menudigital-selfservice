<?php
namespace App\Main\PurchaseRequisition;

interface PurchaseRequisitionRepositoryInterface
{
    public function deleteByRequisitionIdProductId($requisition_id, $product_id): void;
    public function deleteByRequisitionId($requisition_id): void;
}
