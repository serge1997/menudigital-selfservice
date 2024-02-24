<?php

namespace App\Http\Controllers;

use App\Models\menuitems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\StockEntry;
use App\Models\Product;
use App\Models\Saldo;
use App\Models\Technicalfiche;
use App\Main\Stock\StockRepositoryInterface;
use DateTime;
use Exception;
use App\Http\Services\Stock\StockServiceRepository;
use App\Http\Requests\StockEntryRequest;
use App\Http\Services\Requisition\RequisitionRepository;

class StockController extends Controller
{

    protected $supplier;
    protected $stockEntry;
    protected $product;
    protected StockRepositoryInterface $stockRepositoryInterface;

    public function __construct(StockRepositoryInterface $stockRepositoryInterface)
    {
        $this->supplier = new Supplier();
        $this->product = new Product();
        $this->stockRepositoryInterface = $stockRepositoryInterface;
    }

    public function listAllAction(): JsonResponse
    {
        try{
            $deliveryResponse = $this->stockRepositoryInterface->listAllDelevery();
            $deliveryResponse = $deliveryResponse->unique();
            return response()->json($deliveryResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function filterDataTableAction(Request $request): JsonResponse
    {
        try{
            $stockDataTableFilter = $this->stockRepositoryInterface->filterDataTable($request);
            return response()->json($stockDataTableFilter);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function storeStockEntry(StockEntryRequest $request, StockServiceRepository $service, RequisitionRepository $requisition) :JsonResponse
    {
        $request->validated();
        $hoje = new DateTime();
        $hoje = $hoje->format("Y-m-d");
        $produtData = Product::where('id', $request->productID)
            ->first();
        $product = Saldo::select(DB::raw('MAX(emissao) emissao'), 'productID', 'saldoInicial', 'saldoFinal')
            ->where('productID', $request->productID)
                ->groupby('emissao', 'productID', 'saldoInicial', 'saldoFinal')
                    ->first();
        try {
            $requisition->checkRequisitionID($request->requisition_id, $request->productID, $request->quantity);
            $data = $request->all();
            $entry = new StockEntry($data);
            $entry->totalCost = $request->unitCost * $request->quantity;
            $entry->emissao = $hoje;
            $entry->save();
            if ($product):
                if ($produtData->prod_unmed != "bt"):
                    DB::table('saldos')
                            ->where('productID', $request->productID)
                                ->update([
                                    'emissao' => $hoje,
                                    'saldoInicial' => $product->saldoInicial + ($request->quantity * $produtData->prod_contain),
                                    'saldoFinal' => $product->saldoFinal + ($request->quantity * $produtData->prod_contain)
                                ]);
                    $service->CheckRuptureLowStockState($request->productID);
                    return response()
                        ->json('Action registred successfully', 200);
                else:
                    DB::table('saldos')
                        ->where('productID', $request->productID)
                            ->update([
                                'emissao' => $hoje,
                                'saldoInicial' => $product->saldoInicial + $request->quantity,
                                'saldoFinal' => $product->saldoFinal + $request->quantity
                            ]);
                    $service->CheckRuptureLowStockState($request->productID);
                    return response()
                        ->json('Action registred successfully', 200);
                endif;
            else:
                if ($produtData->prod_unmed != "bt"):
                    $saldo = new Saldo();
                    $saldo->productID = $request->productID;
                    $saldo->emissao = $hoje;
                    $saldo->saldoInicial =  $request->quantity * $produtData->prod_contain;
                    $saldo->saldoFinal = $request->quantity * $produtData->prod_contain;
                    $saldo->save();
                else:
                    $saldo = new Saldo();
                    $saldo->productID = $request->productID;
                    $saldo->emissao = $hoje;
                    $saldo->saldoInicial =  $request->quantity;
                    $saldo->saldoFinal = $request->quantity;
                    $saldo->save();
                endif;
                $service->CheckRuptureLowStockState($request->productID);
            endif;
            return response()
                ->json('Action registred successfully', 200);

        }catch(\Exception $e){
            return response()
                ->json($e->getMessage(), 400);
        }

    }

    public function get_stock_stat(): JsonResponse
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
        return response()
            ->json(DB::select($query));
    }

    public function show_technical_fiche(int $id): JsonResponse
    {
        $item = menuitems::where('id', $id)->get();
        $fiche = DB::table('technicalfiches')
            ->select(
                'technicalfiches.itemID',
                'technicalfiches.productID',
                'menuitems.item_name',
                'products.prod_name',
                'technicalfiches.quantity',
                'technicalfiches.cost',
                'products.prod_unmed'
            )
                ->where('itemID', $id)
                    ->join('menuitems', 'technicalfiches.itemID', '=', 'menuitems.id')
                        ->join('products', 'technicalfiches.productID', '=', 'products.id')
                            ->get();

        return response()
            ->json($fiche);
    }


    public function get_inventory(): JsonResponse
    {
        $inventoty = "
            SELECT
            p.prod_name,
            CASE
                WHEN p.prod_unmed <> 'bt' THEN TRUNCATE(sa.saldoInicial / p.prod_contain, 2)
                ELSE
                TRUNCATE(sa.saldoInicial, 2)
            END saldoinicial,
            CASE
                WHEN p.prod_unmed <> 'bt' THEN  TRUNCATE(sa.saldoFinal / p.prod_contain, 2)
                ELSE
                TRUNCATE(sa.saldoFinal, 2)
            END saldofinal
        FROM saldos sa
        INNER JOIN products p
            ON sa.productID = p.id
        ";

        return response()->json(DB::select($inventoty));
    }

    public function resetSaldo(): JsonResponse
    {
        $query = "
            UPDATE saldos SET saldoInicial = saldoFinal
        ";

        DB::select($query);

        return response()->json("Journey reset successffully");
    }

    public function cureentSaldoCheck(StockServiceRepository $stock)
    {
        return $stock->checkAllwaysRupture();
    }

    public function listStockEntryByRequisition($requisition_id): JsonResponse
    {
        try{
            return response()->json($this->stockRepositoryInterface->findStockEntryByRequisition($requisition_id));
        }catch(Exception $e){
            return response()->json($e->getMessage(). ' '.$e->getFile(), 500);
        }
    }

    public function listInventory(Request $request)
    {
        try{
            return response()->json($this->stockRepositoryInterface->getInventory($request));
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @see App\Main\Stock\StockRepositoryInterface
     */
    public function deleteDeliveryByRequisitionIdAction($id, Request $request): JsonResponse
    {
        try{
            $message = "A entrega foi deletada com successo";
            $this->stockRepositoryInterface->deleteDeliveryByRequisitionId($id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @see App\Main\Stock\StockRepositoryInterface
     */
    public function deleteProductFromDeliveryAction($requisition_id, $product_id, Request $request): JsonResponse
    {
        try{
            $message = "Entrega do produto deletada com successo";
            DB::beginTransaction();
            $this->stockRepositoryInterface->deleteProductFromDelivery($requisition_id, $product_id, $request);
            DB::commit();
            return response()->json($message);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateProductDeliveryQuantityAction(Request $request): JsonResponse
    {
        try{
            $message = "A devolução do produto cadastrado com successo";
            DB::beginTransaction();
            $this->stockRepositoryInterface->updateProductDeliveryQuantity($request);
            DB::commit();
            return response()->json($message);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }
}

