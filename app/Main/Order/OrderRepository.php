<?php
namespace App\Main\Order;

use App\Models\Pedido;
use App\Models\ItensPedido;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PaiementType;
use App\Http\Services\UserInstance;
use App\Models\Menuitems;
use App\Models\Role;
use App\Http\Services\Stock\StockServiceRepository;
use App\Http\Services\Util\Util;
use App\Models\Cart;
use Exception;

class OrderRepository implements OrderRepositoryInterface
{
    public array $Order_item_ids = [];
    public array $Order_item_quantitys = [];

    public function getOrders(): Collection
    {
        $orders = Pedido::select(
                'pedidos.id',
                'pedidos.ped_customerName',
                'pedidos.ped_tableNumber',
                'status.stat_desc',
                'pedidos.status_id',
                DB::raw('SUM(itens_pedido.item_total) AS total')
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('status', 'pedidos.status_id', '=', 'status.id')
                        ->where([['pedidos.status_id', PaiementType::PROGRESS], ['ped_delete', false]])
                            ->groupBy(
                                'pedidos.id',
                                'pedidos.ped_customerName',
                                'pedidos.ped_tableNumber',
                                'itens_pedido.item_pedido',
                                'pedidos.status_id',
                                'status.stat_desc'
                                    )
                                    ->orderByRaw('pedidos.created_at DESC')
                                        ->get();

       return new Collection(
            $orders
        );
    }

    public function getOrderItens($id): Collection
    {
        $orderItens = Pedido::select(
                'pedidos.id',
                'pedidos.status_id',
                'itens_pedido.item_id',
                'menuitems.item_name',
                'itens_pedido.item_quantidade',
                'itens_pedido.item_total',
                'itens_pedido.item_price'
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where('pedidos.id', $id)
                            ->get();

        return new Collection($orderItens);
    }

    public function setOrderPaymentStatus($status_id, $pedido_id, $request)
    {
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $confirm):
            if ($confirm->role_id == Role::MANAGER || $confirm->role_id === Role::CAN_USE_CASHIER):
                DB::table('pedidos')
                    ->where('id', $pedido_id)
                    ->update([
                        'status_id' => $status_id
                    ]);
                return true;
            endif;
        endforeach;
        throw new Exception("Voçê não tem permissão");

    }

    public function getOrderHistory(): Collection
    {
        $orderHistory = DB::table('pedidos')
        ->select(
            'pedidos.id',
            'pedidos.ped_customerName',
            'users.name',
            'pedidos.ped_tableNumber',
            'status.stat_desc',
            'pedidos.ped_emissao',
            DB::raw('SUM(item_total) as total')
        )
            ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                ->join('users', 'pedidos.user_id', '=', 'users.id')
                    ->join('status', 'pedidos.status_id', '=', 'status.id')
                        ->where([['pedidos.status_id', '<>', 6], ['pedidos.status_id', '<>', 5]])
                            ->groupBy(
                                'id',
                                'pedidos.ped_customerName',
                                'users.name',
                                'status.stat_desc',
                                'pedidos.ped_tableNumber',
                                'pedidos.ped_emissao'
                            )
                                ->orderBy('pedidos.ped_emissao', 'DESC')
                                    ->get();

        return new Collection($orderHistory);
    }

    public function addNewItemToOrder($request)
    {
        $status = 200;
        $tableNumber = null;
        $orderID = $request->orderID;
        $itemID = $request->itemID;
        $quantity = $request->quantity;
        StockServiceRepository::SetItemSaldoZeroException($tableNumber, $request->itemID);
        StockServiceRepository::checkSetItemSaldoZeroAddItemToOrder($request->itemID);
        StockServiceRepository::StockOutProduct(str_split($itemID, 10), str_split($quantity, 10));
        $auth = $request->session()->get('auth-vue');

        $menuitem = Menuitems::where('id', $itemID)->first();
        $item = ItensPedido::where([
            ['item_pedido', $orderID], ['item_id', $itemID]
        ])->first();
        foreach (UserInstance::get_user_roles($auth) as $confirm):
            if (
                $confirm->role_id === Role::MANAGER ||
                $confirm->role_id === Role::CAN_TAKE_ORDER ||
                $confirm->role_id === Role::CAN_USE_CASHIER ||
                $confirm->role_id === Role::CAN_TRANSFERT_ORDER ||
                $confirm->role_id === Role::CAN_CANCEL_ORDER
            ):
                //DB::beginTransaction();
                if (isset($item)):
                    $totalQuantity = $item->item_quantidade + $quantity;
                    DB::table('itens_pedido')
                        ->where([['item_pedido', $orderID], ['item_id', $itemID]])
                        ->update([
                            'item_quantidade' => $totalQuantity,
                            'item_total' => $menuitem->item_price * $totalQuantity
                        ]);
                        StockServiceRepository::ControleItemLowStockRuptured(str_split($itemID, 10));
                  return $status;
                endif;
                $itens = new ItensPedido();
                $itens->item_pedido     = $orderID;
                $itens->item_id         = $itemID;
                $itens->item_quantidade = $quantity;
                $itens->item_price      = $menuitem->item_price;
                $itens->item_total      = $menuitem->item_price * $quantity;
                $itens->item_emissao    = Util::Today();
                $itens->save();
                StockServiceRepository::ControleItemLowStockRuptured(str_split($itemID, 10));
                return $status;
            endif;
        endforeach;
        throw new Exception("Voçê não tem permissão");
    }

    public function createOrder($request)
    {
        $menuitem = null;
        StockServiceRepository::SetItemSaldoZeroException($request->ped_tableNumber, $menuitem);
        $orderItem = Cart::where('tableNumber', $request->ped_tableNumber)
            ->get();
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $confirm):
            if (
                $confirm->role_id == Role::MANAGER             ||
                $confirm->role_id == Role::CAN_TAKE_ORDER      ||
                $confirm->role_id == Role::CAN_USE_CASHIER     ||
                $confirm->role_id == Role::CAN_TRANSFERT_ORDER ||
                $confirm->role_id == Role::CAN_CANCEL_ORDER
            ):
                $order = new Pedido();
                $order->ped_tableNumber  = $request->ped_tableNumber;
                $order->ped_customerName = $request->ped_customerName;
                $order->user_id          = $auth;
                $order->ped_emissao      = Util::Today();
                $order->status_id        = 6;
                $order->save();

                foreach($orderItem as $itemPedido) {
                    $itens = new ItensPedido();
                    $itens->item_emissao    = Util::Today();
                    $itens->item_pedido     = $order->id;
                    $itens->item_quantidade = $itemPedido->quantity;
                    $itens->item_id         = $itemPedido->item_id;
                    $itens->item_price      = $itemPedido->unit_price;
                    $itens->item_total      = $itemPedido->total;
                    $itens->item_comments   = $itemPedido->comments;
                    $itens->item_option     = $itemPedido->options;
                    $itens->save();
                    $this->Order_item_ids[]       = $itemPedido->item_id;
                    $this->Order_item_quantitys[] = $itemPedido->quantity;
                }
                StockServiceRepository::StockOutProduct($this->Order_item_ids, $this->Order_item_quantitys);
                StockServiceRepository::ControleItemLowStockRuptured($this->Order_item_ids);
                DB::table('carts')->where('tableNumber', $request->ped_tableNumber)
                    ->delete();
                return response()->json("Pedido confirmado", 200);
            endif;
        endforeach;
        Cart::where('tableNumber', $request->ped_tableNumber)
            ->delete();
        throw new Exception("Você não tem permissão");

    }

    public function getOrderTransfert($id): Collection
    {
        $transfertItems = DB::table('itens_pedido')
                ->select(
                    'itens_pedido.item_pedido',
                    'itens_pedido.item_id',
                    'itens_pedido.item_quantidade',
                    'menuitems.item_name'
                )
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where([['item_pedido', $id], ['item_quantidade', '>', 0]])
                            ->get();

        return new Collection($transfertItems);
    }

    public function createTransertItensAction($request)
    {
        $items_id = $request->item_id;
        $item_quantidade = $request->item_quantidade;

        $waiter = Pedido::where('id', $request->item_pedido)
            ->first();

        $auth = $request->session()->get('auth-vue');
        foreach (
            UserInstance::get_user_roles($auth) as $trasnfert
        ):
            if ($trasnfert->role_id === Role::MANAGER ||
                $trasnfert->role_id === Role::CAN_USE_CASHIER
            ):

                $order = new Pedido();
                $order->ped_tableNumber = $request->ped_tableNumber;
                $order->ped_customerName = "Transfered";
                $order->user_id = $waiter->user_id;
                $order->ped_emissao = Util::Today();
                $order->status_id = 6;
                $order->save();

                foreach ($items_id as $key=>$ids):
                    $item = ItensPedido::where('item_pedido', $waiter->id)->where('item_id', $ids)
                        ->first();
                        //foreach ($item_quantidade as $qty):
                            $qty = $item_quantidade[$key];
                            if ($item->item_quantidade == $qty):
                                DB::table('itens_pedido')
                                    ->where('item_pedido', $request->item_pedido)
                                        ->where('item_id', $ids)
                                            ->update([
                                                'item_pedido' => $order->id,
                                            ]);
                            else:

                                if ($qty > $item->item_quantidade):
                                    DB::table('pedidos')
                                        ->where('id', $order->id)
                                            ->delete();

                                        throw new Exception("Quantidade do item superior não é aceitado");
                                endif;
                                DB::table('itens_pedido')
                                    ->where('item_pedido', $request->item_pedido)
                                    ->where('item_id', $ids)
                                        ->update([
                                            'item_quantidade' => $item->item_quantidade - $qty,
                                            'item_total' => $item->item_price * ($item->item_quantidade - $qty)
                                        ]);

                                $tintens = new ItensPedido();
                                $tintens->item_emissao = $item->item_emissao;
                                $tintens->item_quantidade = $qty;
                                $tintens->item_id = $item->item_id;
                                $tintens->item_price = $item->item_price;
                                $tintens->item_total = $item->item_price * $qty;
                                $tintens->item_comments = $item->item_comments;
                                $tintens->item_option = $item->item_option;
                                $tintens->item_pedido = $order->id;
                                $tintens->save();
                            endif;
                        //endforeach;
                endforeach;
                return response()->json("Item transferido com sucesso", 200);
            endif;
        endforeach;
        throw new Exception("You don't have permission");
    }

    public function getOrdersReport()
    {

        $report = DB::table('itens_pedido')
            ->select(
                'menuitems.item_name',
                DB::raw('SUM(itens_pedido.item_quantidade) AS quantidade'),
                DB::raw('SUM(.itens_pedido.item_total) AS total')
            )
                ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                    ->join('pedidos', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                        ->where('item_emissao', Util::Today())
                            ->where([['itens_pedido.item_delete', false], ['pedidos.status_id', '<>', 6]])
                                ->groupby(
                                    'menuitems.item_name'
                                )->get();

        $paiement_data = DB::table('pedidos')
            ->select(DB::raw('SUM(itens_pedido.item_total) cash'), 'status.stat_desc')
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->rightJoin('status', 'pedidos.status_id', '=', 'status.id')
                        ->where('pedidos.ped_emissao', Util::Today())
                            ->groupBy([
                                'status.stat_desc'
                            ])->get();

        $itemCanceled = DB::table('itens_pedido')
                ->select(DB::raw('SUM(item_quantidade * item_price) valor'))
                        ->where([['item_emissao', Util::Today()], ['item_quantidade', '<', 0]])
                            ->get();




        return [
            'report' => $report,
            'paiment' => $paiement_data,
            'valcanceled' => $itemCanceled
        ];
    }
}
