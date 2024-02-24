<?php
namespace App\Main\Stock;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Saldo;
use App\Models\Product;

interface StockRepositoryInterface
{
    public function listAllDelevery();
    public function findLastProductEntry($id);
    public function filterDataTable($request): Collection;
    public function filterCostIntelligence($request, $supplier, $year, $month): Collection;
    public function findStockEntryByRequisition($requisition_id);
    public function getInventory($request);
    public function deleteDeliveryByRequisitionId($id, $request);
    public function reduceSaldo($id): void;
    public function deleteProductFromDelivery($requisition_id ,$product_id, $request): bool;
    public function deleteFromStockEntryByRequisitionId($requisition_id);
    public function reduceFromSaldoAfterDeleteDelivery(Product $product, Saldo $saldo, $requisition_id, $quantity = null);
    public function findStockEntryByRequisitionIdProductId($requisition_id ,$product_id);
    public function findSaldoByProductId(Product $product);
    public function updateProductDeliveryQuantity($request);
}
