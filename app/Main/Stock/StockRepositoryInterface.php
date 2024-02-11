<?php
namespace App\Main\Stock;

use Illuminate\Database\Eloquent\Collection;

interface StockRepositoryInterface
{
    public function findLastProductEntry($id);
    public function filterDataTable($request): Collection;
    public function filterCostIntelligence($request, $supplier, $year, $month): Collection;
    public function findStockEntryByRequisition($requisition_id);
}
