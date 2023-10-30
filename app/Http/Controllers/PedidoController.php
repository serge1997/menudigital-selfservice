<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Pedido;
use App\Models\ItensPedido;
use Illuminate\Support\Facades\DB;
use DateTime;

class PedidoController extends Controller
{
    
    public function  confirmOrder(Request $request)
    {
        $hoje = new DateTime();
        $orderItem = Cart::where('tableNumber', $request->ped_tableNumber)
            ->get();

        $order = new Pedido();
        $order->ped_tableNumber = $request->ped_tableNumber;
        $order->ped_customerName = $request->ped_customerName;
        $order->user_id = $request->user_id;
        $order->ped_emissao = $hoje->format("Y-m-d H:i:s");
        $order->status_id = 6;
        $order->save();

        foreach($orderItem as $itemPedido) {
            $itens = new ItensPedido();
            $itens->item_emissao = $hoje->format("Y-m-d H:i:s");
            $itens->item_pedido = $order->id;
            $itens->item_quantidade = $itemPedido->quantity;
            $itens->item_id = $itemPedido->item_id;
            $itens->item_price = $itemPedido->unit_price;
            $itens->item_total = $itemPedido->total;
            $itens->item_comments = $itemPedido->comments;
            $itens->item_option = $itemPedido->options;
            $itens->save();
        }

        DB::table('carts')->where('tableNumber', $request->ped_tableNumber)
            ->delete();

        return response()->json($orderItem);
    }

    public function getOrderList($table)
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

    public function OperadorOrderList()
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
                        ->where([['pedidos.ped_emissao', $hoje], ['ped_delete', false]])
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

    public function getOrderItem($id)
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

    public function getTablesNumber()
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

    public function  getBillItems($id)
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

    
}
