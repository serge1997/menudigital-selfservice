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
use App\Http\Services\Util\Util;
use Illuminate\Support\Facades\DB;
use App\Main\Product\ProductRepositoryInterface;
use App\Main\PurchaseRequisition\PurchaseRequisitionRepositoryInterface;
use App\Http\Services\Stock\StockServiceRepository;
use App\Http\Services\Requisition\RequisitionRepository;
use App\Models\Product;
use App\Traits\Permission;

class StockRepository implements StockRepositoryInterface
{
    use Permission;
    public $bar = [1, 5];
    public $kitchen = [2, 3, 4, 6];
    private ProductRepositoryInterface $productRepositoryInterface;
    private PurchaseRequisitionRepositoryInterface $purchaseRequisitionRepositoryInterface;
    private StockServiceRepository $serviceStock;
    private RequisitionRepository $serviceRequisition;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        PurchaseRequisitionRepositoryInterface $purchaseRequisitionRepositoryInterface,
        StockServiceRepository $serviceStock,
        RequisitionRepository $serviceRequisition

    ){
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->purchaseRequisitionRepositoryInterface = $purchaseRequisitionRepositoryInterface;
        $this->serviceStock = $serviceStock;
        $this->serviceRequisition = $serviceRequisition;
    }

    public function storeStockEntry($request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            $productData = $this->productRepositoryInterface->findById($request->productID);
            $product = $this->findSaldoByProductId($productData);
            $this->serviceRequisition->checkRequisitionID($request->requisition_id, $request->productID, $request->quantity);
            $data = $request->all();
            $entry = new StockEntry($data);
            $entry->totalCost = $request->unitCost * $request->quantity;
            $entry->emissao = Util::Today();
            $entry->save();
            if ($product):
                if ($productData->prod_unmed != "bt"):
                    DB::table('saldos')
                            ->where('productID', $request->productID)
                                ->update([
                                    'emissao' => Util::Today(),
                                    'saldoInicial' => $product->saldoInicial + ($request->quantity * $productData->prod_contain),
                                    'saldoFinal' => $product->saldoFinal + ($request->quantity * $productData->prod_contain)
                                ]);
                    $this->serviceStock->CheckRuptureLowStockState($request->productID);
                    return true;
                else:
                    DB::table('saldos')
                        ->where('productID', $request->productID)
                            ->update([
                                'emissao' => Util::Today(),
                                'saldoInicial' => $product->saldoInicial + $request->quantity,
                                'saldoFinal' => $product->saldoFinal + $request->quantity
                            ]);
                    $this->serviceStock->CheckRuptureLowStockState($request->productID);
                    return true;
                endif;
            else:
                if ($productData->prod_unmed != "bt"):
                    $saldo = new Saldo();
                    $saldo->productID = $request->productID;
                    $saldo->emissao = Util::Today();
                    $saldo->saldoInicial =  $request->quantity * $productData->prod_contain;
                    $saldo->saldoFinal = $request->quantity * $productData->prod_contain;
                    $saldo->save();
                else:
                    $saldo = new Saldo();
                    $saldo->productID = $request->productID;
                    $saldo->emissao = Util::Today();
                    $saldo->saldoInicial =  $request->quantity;
                    $saldo->saldoFinal = $request->quantity;
                    $saldo->save();
                endif;
                $this->serviceStock->CheckRuptureLowStockState($request->productID);
            endif;
            return true;
        }
        throw new Exception(Util::PermisionExceptionMessage());
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
            StockEntry::where([
                ['requisition_id', $requisition_id],
                ['is_delete', false]
            ])->get()
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
    public function deleteFromStockEntryByRequisitionId($requisition_id)
    {
        StockEntry::where('requisition_id', $requisition_id)
            ->update([
                'is_delete' => true,
                'emissao' => Util::Today()
            ]);
    }
    /**
     * delete all elemente delivery products
     */
    public function deleteDeliveryByRequisitionId($id, $request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            $this->reduceSaldo($id);
            $this->deleteFromStockEntryByRequisitionId($id);
            $this->purchaseRequisitionRepositoryInterface->deleteByRequisitionId($id);
            return true;
        }
        throw new Exception(Util::PermisionExceptionMessage());
    }
    public function reduceSaldo($id): void
    {
        $deliveryItems = $this->findStockEntryByRequisition($id);
        foreach ($deliveryItems as $item){
            $product = $this->productRepositoryInterface->findById($item->productID);
            $saldo = $this->findSaldoByProductId($product);
            $this->reduceFromSaldoAfterDeleteDelivery($product, $saldo, $id);
            $this->purchaseRequisitionRepositoryInterface->deleteByRequisitionIdProductId($id, $product->id);
        }
    }

    public function reduceFromSaldoAfterDeleteDelivery(Product $product, Saldo $saldo, $requisition_id, $quantity = null)
    {
        $stock = $this->findStockEntryByRequisitionIdProductId($requisition_id, $product->id);
        if ($saldo->saldoFinal >= $stock->quantity && is_null($quantity)){
            if ($product->prod_unmed== "bt"){
                Saldo::where('productID', $product->id)
                    ->update([
                        'saldoFinal' => $saldo->saldoFinal - $stock->quantity,
                        'saldoInicial' => $saldo->saldoInicial - $stock->quantity
                    ]);
            }else{
                Saldo::where('productID', $product->id)
                    ->update([
                        'saldoFinal' => $saldo->saldoFinal - ($stock->quantity * $product->prod_contain),
                        'saldoInicial' => $saldo->saldoInicial - ($stock->quantity * $product->prod_conatain),
                    ]);
            }
            return true;
        }else {
            if ($quantity <= $saldo->saldoFinal && $quantity > $stock->$quantity){
                if ($product->prod_unmed == "bt"){
                    Saldo::where('productID', $product->id)
                        ->update([
                            'saldoFinal' => $saldo->saldoFinal - $quantity,
                            'saldoInicial' => $saldo->saldoInicial - $quantity
                        ]);
                }else{
                    Saldo::where('productID', $product->id)
                        ->update([
                            'saldoFinal' => $saldo->saldoFinal - ($quantity * $product->prod_contain),
                            'saldoInicial' => $saldo->saldoInicial - ($quantity * $product->prod_conatain),
                        ]);
                }
                return true;
            }
        }
        throw new Exception("Saldo indisponivel para realizar devolução");
    }

    public function findStockEntryByRequisitionIdProductId($requisition_id ,$product_id)
    {
        return StockEntry::where([
                ['requisition_id', $requisition_id],
                ['productID', $product_id],
                ['is_delete', false]
            ])->first();
    }

    public function findSaldoByProductId(Product $product)
    {
        return  Saldo::where('productID', $product->id)->first();
    }
    public function deleteProductFromDelivery($requisition_id ,$product_id, $request): bool
    {
        // var_dump(StockEntry::where('requisition_id', $requisition_id)->count()); die;
        $product = $this->productRepositoryInterface->findById($product_id);
        $saldo = $this->findSaldoByProductId($product);
       if ($this->can_manage($request) || $this->can_create_product($request)){
            $this->reduceFromSaldoAfterDeleteDelivery($product, $saldo, $requisition_id);
            if (StockEntry::where('requisition_id', $requisition_id)->count() > 1){
                $this->deleteFromStockEntryByRequisitionId($requisition_id);
                $this->purchaseRequisitionRepositoryInterface
                    ->deleteByRequisitionIdProductId($requisition_id, $product_id);
            }else{
                $this->deleteFromStockEntryByRequisitionId($requisition_id);
                $this->purchaseRequisitionRepositoryInterface
                    ->deleteByRequisitionIdProductId($requisition_id, $product_id);
                $this->purchaseRequisitionRepositoryInterface
                    ->deleteByRequisitionId($requisition_id);
            }
            return true;
       }
        throw new Exception(Util::PermisionExceptionMessage());
    }
    public function updateProductDeliveryQuantity($request)
    {
        $emissao = new \DateTime();
        $emissao = $emissao->format('Y-m-d');
        if ($this->can_manage($request) || $this->can_create_product($request)){
            $productDelivred = $this->findStockEntryByRequisitionIdProductId($request->requisition_id, $request->product_id);
            if ($request->quantity < $productDelivred->quantity){
                StockEntry::where([
                    ['productID', $request->product_id],
                    ['requisition_id', $request->requisition_id],
                    ['is_delete', false]
                ])->update([
                    'totalCost' => $productDelivred->totalCost - ($productDelivred->unitCost * $request->quantity),
                    'quantity' => $productDelivred->quantity - $request->quantity,
                    'emissao' => $emissao
                ]);
                $stock = new StockEntry();
                $stock->requisition_id = $request->requisition_id;
                $stock->unitCost = $productDelivred->unitCost;
                $stock->productID = $request->product_id;
                $stock->totalCost = $productDelivred->unitCost * $request->quantity;
                $stock->supplierID = $productDelivred->supplierID;
                $stock->quantity = $request->quantity;
                $stock->emissao = $emissao;
                $stock->is_delete = true;
                $stock->save();
                $product = $this->productRepositoryInterface->findById($request->product_id);
                $saldo = $this->findSaldoByProductId($product);
                $this->reduceFromSaldoAfterDeleteDelivery($product, $saldo, $request->requisition_id, $request->quantity);
                return true;
            }else {
                throw new Exception("quantidade informada invalida");
            }
        }
        throw new Exception(Util::PermisionExceptionMessage());
    }

    public function findAllDevolution()
    {
        return PurchaseRequisitionResource::collection(
            PurchaseRequisition::select('*')
                ->whereIn('id', function($query){
                    $query->select('requisition_id')
                        ->from('stock_entries')
                            ->where('is_delete', true);
                })->get()
        );
    }

    public function findDevolutionItemsByRequisitionId($requisition_id)
    {
        return StockEntryResource::collection(StockEntry::where([
            ['is_delete', true],
            ['requisition_id', $requisition_id]]
        )->get());
    }
    public function listStockProductStat()
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
        return DB::select($query);
    }

    public function resetSaldo()
    {
        $query = "UPDATE saldos SET saldoInicial = saldoFinal";
        DB::select($query);
    }

    public function cureentSaldoCheck()
    {
        $this->serviceStock->checkAllwaysRupture();
    }
}
