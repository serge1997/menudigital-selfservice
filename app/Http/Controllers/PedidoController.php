<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Pedido;
use App\Models\ItensPedido;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Events\StockReduced;
use App\Models\Menuitems;
use App\Models\Technicalfiche;
use App\Models\Saldo;
use App\Models\PaiementType;
use App\Http\Services\UserInstance;
use App\Models\Role;
use App\Events\IsRuputureEvent;
use App\Http\Services\Stock\StockServiceRepository;


class PedidoController extends Controller
{
    public $hoje ;
    public $orderStatus;
    public array $Order_item_ids = [];
    public array $Order_item_quantitys = [];

    public function __construct()
    {
        $date = new DateTime();
        $this->hoje = $date->format('Y-m-d');
        $this->orderStatus = new PaiementType();
    }

    public function getOrderList($table): JsonResponse
    {
        $hoje = new DateTime();
        $hoje = $hoje->format("Y-m-d");

        $order = DB::table('pedidos')
            ->select(
                'menuitems.item_name',
                'itens_pedido.item_quantidade',
                'menuitems.item_price',
                'itens_pedido.item_total',
                'pedidos.id AS pedido_id'
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where('ped_tableNumber', $table)
                            ->where('pedidos.ped_emissao', $hoje)
                                ->where('pedidos.status_id', 6)
                                    ->get();

        return response()->json($order);

    }

    public function OperadorOrderList(): JsonResponse
    {
        $hoje = new DateTime();
        $hoje = $hoje->format("Y-m-d");

        $order = DB::table('pedidos')
            ->select(
                'pedidos.id',
                'pedidos.ped_customerName',
                'pedidos.ped_tableNumber',
                'status.stat_desc',
                'pedidos.status_id',
                DB::raw('SUM(itens_pedido.item_total) AS total')
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('status', 'pedidos.status_id', '=', 'status.id')
                        ->where([['pedidos.status_id', $this->orderStatus->getProgress()], ['ped_delete', false]])
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

        $status = DB::table('status')
            ->get();
        return response()->json([
            'order' => $order,
            'status' => $status
        ]);
    }

    public function  confirmOrder(Request $request, StockServiceRepository $stockservice): JsonResponse
    {
        $orderItem = Cart::where('tableNumber', $request->ped_tableNumber)
            ->get();
        $user_auth = $request->session()->get('auth-vue');
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $confirm):
            if (
                $confirm->role_id === Role::MANAGER             ||
                $confirm->role_id === Role::CAN_TAKE_ORDER      ||
                $confirm->role_id === Role::CAN_USE_CASHIER     ||
                $confirm->role_id === Role::CAN_TRANSFERT_ORDER ||
                $confirm->role_id === Role::CAN_CANCEL_ORDER
            ):

                $order = new Pedido();
                $order->ped_tableNumber = $request->ped_tableNumber;
                $order->ped_customerName = $request->ped_customerName;
                $order->user_id = $user_auth;
                $order->ped_emissao = $this->hoje;
                $order->status_id = 6;
                $order->save();

                foreach($orderItem as $itemPedido) {
                    $itens = new ItensPedido();
                    $itens->item_emissao = $this->hoje;
                    $itens->item_pedido = $order->id;
                    $itens->item_quantidade = $itemPedido->quantity;
                    $itens->item_id = $itemPedido->item_id;
                    $itens->item_price = $itemPedido->unit_price;
                    $itens->item_total = $itemPedido->total;
                    $itens->item_comments = $itemPedido->comments;
                    $itens->item_option = $itemPedido->options;
                    $itens->save();
                    $this->Order_item_ids[] = $itemPedido->item_id;
                    $this->Order_item_quantitys[] = $itemPedido->quantity;
                }
                $stockservice->StockOutProduct($this->Order_item_ids, $this->Order_item_quantitys);
                DB::table('carts')->where('tableNumber', $request->ped_tableNumber)
                    ->delete();
                $stockservice->ControleItemLowStockRupured($this->Order_item_ids);
                return response()->json("Pedido confirmado", 200);
            endif;
        endforeach;

        return response()->json("You don't have permission", 422);
    }

    public function getOrderItem($id): JsonResponse
    {
        $itens = DB::table('pedidos')
            ->select(
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

        return response()->json($itens);
    }

    public function UpdateOrderStatus($id, $pedido)
    {
        DB::table('pedidos')
            ->where('id', $pedido)
                ->update([
                    'status_id' => $id
                ]);
    }

    public function getTablesNumber(): JsonResponse
    {
        $hoje = new DateTime();
        $hoje = $hoje->format("Y-m-d");

        $tables = DB::table('tablenumber')->select('table')
            ->whereNotIn('id', function($query) {
                $hoje = new DateTime();
                $hoje = $hoje->format("Y-m-d");
                $query->select('ped_tableNumber')
                    ->from('pedidos')
                        ->where('ped_emissao', $hoje)
                            ->where([['status_id', '=', 6], ['ped_delete', 0]]);
            })->get();


        $busyTables = DB::table('pedidos')
            ->select('pedidos.ped_tableNumber','pedidos.id', 'users.name')
                ->join('users', 'pedidos.user_id', '=', 'users.id')
                    ->where([['pedidos.status_id', '=', 6], ['pedidos.ped_delete', 0]])
                        ->where('pedidos.ped_emissao', $hoje)
                            ->get();

        return response()->json([
            'tables' => $tables,
            'busy_tables' =>$busyTables
        ]);
    }

    public function  getBillItems($id): JsonResponse
    {
        $bill = DB::table('pedidos')
            ->select(
                'menuitems.item_name',
                'itens_pedido.item_price',
                'itens_pedido.item_quantidade',
                'itens_pedido.item_total'
            )
                ->join('itens_pedido', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where('pedidos.id', $id)
                            ->get();

        return response()->json($bill);
    }

    public function Add_To_Order(Request $request, $id)
    {
        $menuitem = Menuitems::where('id', $id)->first();
        $orderID = $request->orderID;
        $tableNumber = $request->tableNumber;
        $check_if_item_exist = ItensPedido::where([['item_pedido', $orderID], ['item_id', $id]])
            ->first();


        /*if ($check_if_item_exist):
            DB::table('itens_pedido')
                ->where([['item_pedido', $orderID], ['item_id', $id]])
                    ->update([
                        'item_quantidade' => $check_if_item_exist->item_quantidade,
                        'item_total' => $menuitem->item_price * $check_if_item_exist->item_quantidade
                    ]);
                return response()
                    ->json([
                        'item' => $menuitem,
                        'order' => ItensPedido::where([['item_pedido', $orderID], ['item_id', $id]])->first()
                    ]);
        endif;*/

        /*$itens = new ItensPedido();
        $itens->item_pedido = $orderID;
        $itens->item_id = $id;
        $itens->item_quantidade = 0;
        $itens->item_price = $menuitem->item_price;
        $itens->item_total = $menuitem->item_price;
        $itens->item_emissao = $this->hoje;
        $itens->save();*/

        return response()
            ->json([
                'item' => $menuitem,
                'order' => ItensPedido::where([['item_pedido', $orderID], ['item_id', $id]])->first()

            ]);


    }

    public function postNewOrderItem(Request $request): JsonResponse
    {
        $orderID = $request->orderID;
        $itemID = $request->itemID;
        $quantity = $request->quantity;
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

                if ($item):
                    $totalQuantity = $item->item_quantidade + $quantity;
                    DB::table('itens_pedido')
                        ->where([['item_pedido', $orderID], ['item_id', $itemID]])
                            ->update([
                                'item_quantidade' => $item->item_quantidade += $quantity,
                                'item_total' => $menuitem->item_price * $totalQuantity
                            ]);

                    $fiche = Technicalfiche::where('itemID', $menuitem->id)->get();

                    foreach ($fiche as $product){
                        $old_saldo = Saldo::where('productID', $product->productID)->first();

                        if ($old_saldo->emissao == $this->hoje):
                            DB::table('saldos')
                                ->where('productID', $product->productID)
                                    ->update([
                                        'saldoFinal' => $old_saldo->saldoFinal - $product->quantity,
                                    ]);
                        else:
                             DB::table('saldos')
                                 ->where('productID', $item->productID)
                                     ->update([
                                         'emissao' => $this->hoje,
                                         'saldoInicial' => $old_saldo->saldoFinal,
                                         'saldoFinal' => $old_saldo->saldoFinal - $product->quantity
                                     ]);
                        endif;
                    }
                    return response()
                        ->json("Item adicionado com sucesso", 200);
                endif;
                $itens = new ItensPedido();
                $itens->item_pedido = $orderID;
                $itens->item_id = $itemID;
                $itens->item_quantidade = $quantity;
                $itens->item_price = $menuitem->item_price;
                $itens->item_total = $menuitem->item_price * $quantity;
                $itens->item_emissao = $this->hoje;
                $itens->save();
                event(new StockReduced($itens));

                return response()
                    ->json("Item adicionado com sucesso ", 200);
            endif;
        endforeach;
        return response()->json("You don't have permission", 422);
    }

    public function getBillHistory(): JsonResponse
    {
        $bills = DB::table('pedidos')
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
        return response()->json($bills);
    }
}
