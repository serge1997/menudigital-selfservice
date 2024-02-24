<?php
namespace App\Main\PurchaseRequisition;

interface PurchaseRequisitionRepositoryInterface
{
    public function create($request);
    public function listAll();
    public function findById($id);
    public function deleteByRequisitionIdProductId($requisition_id, $product_id);
    public function deleteByRequisitionId($requisition_id);
}
