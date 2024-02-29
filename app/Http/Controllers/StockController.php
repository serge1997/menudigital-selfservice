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

    public function storeStockEntryAction(StockEntryRequest $request) :JsonResponse
    {
        try{
            $message = "Entrega do produto salvou com sucesso !";
            DB::beginTransaction();
            $this->stockRepositoryInterface->storeStockEntry($request);
            DB::commit();
            return response()->json($message);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

    }

    public function get_stock_stat(): JsonResponse
    {
        try{
            return response()->json($this->stockRepositoryInterface->listStockProductStat());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function resetSaldoAction(): JsonResponse
    {
        try{
            $message = "Journey reset successffully";
            $this->stockRepositoryInterface->resetSaldo();
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function cureentSaldoCheckAction()
    {
        try{
            $this->stockRepositoryInterface->cureentSaldoCheck();
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
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
            DB::beginTransaction();
            $this->stockRepositoryInterface->deleteDeliveryByRequisitionId($id, $request);
            DB::commit();
            return response()->json($message);
        }catch(Exception $e){
            DB::rollBack();
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

    public function findAllDevolutionAction(): JsonResponse
    {
        try{
            return response()
                ->json($this->stockRepositoryInterface->findAllDevolution());
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
    public function findDevolutionItemsByRequisitionIdAction($requisition_id): JsonResponse
    {
        try{
            return response()
                ->json($this->stockRepositoryInterface->findDevolutionItemsByRequisitionId($requisition_id));
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}

