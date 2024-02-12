<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Main\Order\OrderRepositoryInterface;
use App\Main\OrderStatus\OrderStatusRepositoryInterface;
use Exception;


class OrderController extends Controller
{
    protected OrderRepositoryInterface $orderRepositotyInterface;

    public function __construct(
        OrderRepositoryInterface $orderRepositotyInterface
    )
    {
        $this->orderRepositotyInterface = $orderRepositotyInterface;
    }

    public function OperadorOrderList(OrderStatusRepositoryInterface $orderStatusRepositoryInterface): JsonResponse
    {
        try {

            $orderListResponse = $this->orderRepositotyInterface->getOrders();
            $statusListResponse = $orderStatusRepositoryInterface->getAll();
            return response()->json([
                'order' => $orderListResponse,
                'status' => $statusListResponse
            ]);

        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function  confirmOrder(Request $request): JsonResponse
    {
        try {
            $request->validate(['ped_customerName' => ['required']],
            [
                'ped_customerName.required' => "customer name is required"
            ]);
            $message = "Pedido salvou com sucesso";
            $this->orderRepositotyInterface->createOrder($request);
            return response()->json($message);

        }catch (Exception $e){
            return \response()->json($e->getMessage(), 500);
        }
    }

    public function listOrderItens($id): JsonResponse
    {
        try {

            $orderItenResponse = $this->orderRepositotyInterface->getOrderItens($id);
            return response()->json($orderItenResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function orderPayment($status_id, $pedido_id, Request $request): JsonResponse
    {
        try{
            $message = "Pagamento realisado com successo";
            $this->orderRepositotyInterface->setOrderPaymentStatus($status_id, $pedido_id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }

    }

    public function postNewOrderItem(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $message = "Item adicionado com sucesso";
            $this->orderRepositotyInterface->addNewItemToOrder($request);
            DB::commit();
            return response()->json($message);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getOrderHistory(Request $request): JsonResponse
    {
        try{
            $orderHistoryResponse = $this->orderRepositotyInterface->getOrderHistory($request);
            return response()->json($orderHistoryResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function updateHistoryOrderStatusAction($order_id, Request $request): JsonResponse
    {
        try{
            $message = "Pedido editado com sucesso";
            $this->orderRepositotyInterface->updateHistoryOrderStatus($order_id, $request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * HTTP METHOD GET
     * listar os itens a transferir
     * @param $id
     */
     public function listOrderTransfertItens($id): JsonResponse
     {
        try {
            $apiTrasnfertItemResponse = $this->orderRepositotyInterface->getOrderTransfert($id);
            return response()->json($apiTrasnfertItemResponse);
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
     }

     /**
     * HTTP METHOD GET
     * ação transferir itens
     */
     public function createOrderTransfertAction(Request $request)
     {
        try {
            $message = "Item transferido com succeso";
            $this->orderRepositotyInterface->createTransertItensAction($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
     }

     /**
     * HTTP METHOD GET
     */

     public function getOrderReportAction(): JsonResponse
     {
        try {

            $apiReportResponse = $this->orderRepositotyInterface->getOrdersReport();
            return response()->json([
                'report' => $apiReportResponse["report"],
                'paiment' => $apiReportResponse["paiment"],
                'valcanceled' => $apiReportResponse["valcanceled"]
            ]);
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
     }

     public function cancelOrderItemAction(Request $request): JsonResponse
     {
        try{

            $message = "Item cancelado com sucesso";
            $this->orderRepositotyInterface->cancelOrderItem($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
     }

     public function cancelOrderAction(Request $request): JsonResponse
     {
        try{
            $message = "Pedido cancelado com sucesso";
            $this->orderRepositotyInterface->cancelOrder($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
     }
}
