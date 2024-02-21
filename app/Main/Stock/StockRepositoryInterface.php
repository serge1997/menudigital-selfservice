<?php
namespace App\Main\Stock;

use Illuminate\Database\Eloquent\Collection;

interface StockRepositoryInterface
{
    public function listAllDelevery();
    public function findLastProductEntry($id);
    public function filterDataTable($request): Collection;
    public function filterCostIntelligence($request, $supplier, $year, $month): Collection;
    public function findStockEntryByRequisition($requisition_id);
    public function getInventory($request);
    public function deleteDeliveryByRequisitionId($id, $request);
    public function reduceSaldoAfterDeleteDelivery($id): void;
}
