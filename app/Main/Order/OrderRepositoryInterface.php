<?php
namespace App\Main\Order;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function getOrders(): Collection;
    public function getOrderItens($id): Collection;
    public function createOrder($request);
    public function setOrderPaymentStatus($request);
    public function getOrderHistory($request): Collection;
    public function updateHistoryOrderStatus($order_id, $request): void;
    public function addNewItemToOrder($request);
    public function getOrderTransfert($id): Collection;
    public function createTransertItensAction($request);
    public function getOrdersReport();
    public function cancelOrderItem($request);
    public function cancelOrder($request);
    public function returnItem($item_id, $quantidade): void;

    public function findByQrCodeOrderNumber(int $qrcode_order_number);
    public function orderInWaitingStatus($qrcode_order_number): bool;
    public function findOrderByQrCodeNumber($qrcode_order_number, $item_id);

}
