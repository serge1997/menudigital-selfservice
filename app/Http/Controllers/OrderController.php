<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Main\Order\OrderRepositoryInterface;
use App\Main\OrderStatus\OrderStatusRepositoryInterface;
use App\Models\Cart;
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
            $message = __('messages.create', ['model' => 'Order']);
            DB::beginTransaction();
            $this->orderRepositotyInterface->createOrder($request);
            DB::commit();
            return response()->json($message);
        }catch (Exception $e){
            DB::rollBack();
            Cart::where('tableNumber', $request->ped_tableNumber)->delete();
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

    public function orderPayment(Request $request): JsonResponse
    {
        try{
            $message = __('messages.create', ['model' => 'Payment']);
            $this->orderRepositotyInterface->setOrderPaymentStatus($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }

    }

    public function postNewOrderItem(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $message = __('messages.add_to_order');
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
            $message = __('messages.update');
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
            $message = __('messages.create_transfert');
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

            $message = __('messages.canceled');
            $this->orderRepositotyInterface->cancelOrderItem($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
     }

     public function cancelOrderAction(Request $request): JsonResponse
     {
        try{
            $message = __('messages.canceled');
            $this->orderRepositotyInterface->cancelOrder($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
     }
}
