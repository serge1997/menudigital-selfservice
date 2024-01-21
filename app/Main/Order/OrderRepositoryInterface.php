<?php
namespace App\Main\Order;

use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function getOrders(): Collection;
    public function getOrderItens($id): Collection;
    public function createOrder($request);
    public function setOrderPaymentStatus($status_id, $pedido_id, $request);
    public function getOrderHistory(): Collection;
    public function addNewItemToOrder($request);
    public function getOrderTransfert($id): Collection;
    public function createTransertItensAction($request);
    public function getOrdersReport();
}
