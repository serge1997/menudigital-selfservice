<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItensPedido;
use App\Models\Pedido;
use DateTime;
class OrderTransfertController extends Controller
{
    
    public function getTransfertOrderItems($id)
    {
        $items = DB::table('itens_pedido')
                ->select(
                    'itens_pedido.item_pedido',
                    'itens_pedido.item_id',
                    'menuitems.item_name'
                )
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where('item_pedido', $id)
                            ->get();

        return response()->json($items);
    }

    public function postTransfert(Request $request)
    {
        $items_id = $request->item_id;

        $waiter = Pedido::where('id', $request->item_pedido)
            ->first();

        $hoje = new DateTime();

        $order = new Pedido();
        $order->ped_tableNumber = $request->ped_tableNumber;
        $order->ped_customerName = "Transfered";
        $order->user_id = $waiter->user_id;
        $order->ped_emissao = $hoje->format("Y-m-d H:i:s");
        $order->status_id = 6;
        $order->save();
        foreach ($items_id as $ids):

            DB::table('itens_pedido')
                ->where('item_pedido', $request->item_pedido)
                    ->where('item_id', $ids)
                        ->update([
                            'item_pedido' => $order->id,
                        ]);
        endforeach;

        return response()->json("Pedido transferido com sucesso !");
    }

    public function getReport()
    {
        $hoje = new DateTime();
        $hoje = $hoje->format('Y-m-d');

        $report = DB::table('itens_pedido')
            ->select(
                'menuitems.item_name',
                DB::raw('SUM(itens_pedido.item_quantidade) AS quantidade'),
                DB::raw('SUM(.itens_pedido.item_total) AS total')
            )
                ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                    ->where('item_emissao', $hoje)
                        ->where('item_delete', false)
                            ->groupby(
                                'menuitems.item_name'
                            )
                                ->get();

        return response()->json($report);
    }
}
