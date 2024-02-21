<?php
namespace App\Main\Stock;

use App\Models\StockEntry;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\StockEntryResource;
use App\Models\Saldo;
use App\Models\PurchaseRequisition;
use App\Models\RequisitionItem;
use App\Http\Resources\PurchaseRequisitionResource;
use App\Http\Resources\SaldoResource;
use Illuminate\Support\Facades\DB;
use App\Main\Product\ProductRepositoryInterface;
use App\Main\PurchaseRequisition\PurchaseRequisitionRepositoryInterface;
use App\Traits\Permission;

class StockRepository implements StockRepositoryInterface
{
    use Permission;
    public $bar = [1, 5];
    public $kitchen = [2, 3, 4, 6];
    private ProductRepositoryInterface $productRepositoryInterface;
    private PurchaseRequisitionRepositoryInterface $purchaseRequisitionRepositoryInterface;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        PurchaseRequisitionRepositoryInterface $purchaseRequisitionRepositoryInterface
    ){
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->purchaseRequisitionRepositoryInterface = $purchaseRequisitionRepositoryInterface;
    }
    public function listAllDelevery()
    {
        return PurchaseRequisitionResource::collection(
            PurchaseRequisition::select('*')
                ->whereIn('id', function($query){
                    $query->select('requisition_id')
                        ->from('stock_entries')
                            ->where('is_delete', false);
                })->get()
        );
    }
    public function findLastProductEntry($id)
    {
        $productInfo = StockEntry::where('productID', $id)
            ->orderBy('emissao', 'DESC')
                ->first();
        if (!isset($productInfo->productID)){
            throw new Exception("Produto não tem custo. Custo necessario para processar a ficha tecnica");
        }

        return $productInfo;
    }

    public function filterDataTable($request): Collection
    {
        $query = "
            SELECT
                MAX(st.emissao) emissao,
                max(st.unitCost) unitCost,
                st.productID,
                p.prod_name,
                p.min_quantity,
                sp.sup_name,
                CASE
                    WHEN p.prod_unmed = 'bt' THEN TRUNCATE(sa.saldoFinal, 2)
                ELSE
                    TRUNCATE(sa.saldoFinal / p.prod_contain, 2)
                END saldoFinal,
                p.prod_unmed
            FROM stock_entries st
                INNER JOIN saldos sa
                    ON sa.productID = st.productID
                INNER join products p
                    ON p.id = st.productID
                    AND p.is_delete = 0
                INNER JOIN suppliers sp
                    ON sp.id = st.supplierID
            WHERE p.prod_name LIKE '%{$request->search_param}%' OR sp.sup_name LIKE '%{$request->search_param}%'
            GROUP BY
                st.productID,
                p.prod_name,
                sp.sup_name,
                p.prod_unmed,
                p.prod_contain,
                p.min_quantity,
                saldoFinal
            HAVING MAX(st.emissao)
            ORDER BY p.prod_name
        ";
        return new Collection(
            DB::select($query)
        );
    }

    public function filterCostIntelligence($request, $supplier, $year, $month): Collection
    {
        $condition_like = "";
        if (!is_null($supplier)) {
            $condition_like .= "supp.sup_name LIKE %'".$supplier."'% AND ";
        }
        if ($year) {
            $condition_like .= "SUBSTRING(st.emissao, 1, 4) LIKE '%".$year."%' AND ";
        }
        if ($month){
            $condition_like .= "SUBSTRING(MONTHNANE(st.emissao), 1, 3) LIKE '%".$month."%' AND ";
        }

        $condition_like = "supp.is_delete <> 0 AND st.is_delete <> 0";
        $query = "
            SELECT
                MAX(st.emissao) emissao,
                max(st.unitCost) unitCost,
                st.productID,
                p.prod_name,
                p.min_quantity,
                sp.sup_name,
                CASE
                    WHEN p.prod_unmed = 'bt' THEN TRUNCATE(sa.saldoFinal, 2)
                ELSE
                    TRUNCATE(sa.saldoFinal / p.prod_contain, 2)
                END saldoFinal,
                p.prod_unmed
            FROM stock_entries st
                INNER JOIN saldos sa
                    ON sa.productID = st.productID
                INNER join products p
                    ON p.id = st.productID
                    AND p.is_delete = 0
                INNER JOIN suppliers sp
                    ON sp.id = st.supplierID
            WHERE {$condition_like}
            GROUP BY
                st.productID,
                p.prod_name,
                sp.sup_name,
                p.prod_unmed,
                p.prod_contain,
                p.min_quantity,
                saldoFinal
            HAVING MAX(st.emissao)
            ORDER BY p.prod_name
        ";

        return new Collection(
            $query
        );
    }

    public function findStockEntryByRequisition($requisition_id)
    {
        return StockEntryResource::collection(
            StockEntry::where('requisition_id', $requisition_id)
                ->get()
            );
    }

    public function getInventory($request)
    {
        $query = Saldo::select(
            DB::raw('DISTINCT saldos.productID'),
            'saldos.saldoFinal',
            'saldos.saldoInicial',
            'saldos.emissao'
        )
            ->join('technicalfiches AS te', 'te.productID', '=', 'saldos.productID')
                ->join('menuitems AS me', 'me.id', '=', 'te.itemID')
                    ->whereIn('me.type_id', $request->department == 1 ? $this->bar : $this->kitchen)
                        ->get();
        return SaldoResource::collection($query);
    }
    public function deleteDeliveryByRequisitionId($id, $request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            StockEntry::where('requisition_id', $id)
            ->update([
                'is_delete' => true
            ]);
            $this->purchaseRequisitionRepositoryInterface->deleteByRequisitionId($id);
            $this->reduceSaldoAfterDeleteDelivery($id);
        }
    }
    public function reduceSaldoAfterDeleteDelivery($id): void
    {
        $deliveryItems = $this->findStockEntryByRequisition($id);
        foreach ($deliveryItems as $item){
            $product = $this->productRepositoryInterface->findById($item->productID);
            $saldo = Saldo::where('productID', $product->id)->first();
            $this->purchaseRequisitionRepositoryInterface->deleteByRequisitionIdProductId($id, $product->id);
            if ($product->prod_unmed == "bt"){
                Saldo::where('productID', $product->id)
                    ->update([
                        'saldoFinal' => $saldo->saldoFinal - $item->quantity,
                        'saldoInicial' => $saldo->saldoInicial - $item->quantity
                    ]);
            }else{
                Saldo::where('productID', $product->id)
                    ->update([
                        'saldoFinal' => $saldo->saldoFinal - ($item->quantity * $product->prod_contain),
                        'saldoInicial' => $saldo->saldoInicial - ($item->quantity * $product->prod_conatain),
                    ]);
            }
        }
    }


}
