<?php
namespace App\Http\Services\Requisition;

interface RequisitionInterface
{
    public function checkRequisitionID($id, $product_id);
}
