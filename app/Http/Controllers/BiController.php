<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ItensPedido;

class BiController extends Controller
{
    public function waiterStat(){
        $waiter = DB::table("pedidos")
            ->select(
                'users.name',
                DB::raw('SUM(itens_pedido.item_total) AS venda'),
                DB::raw('AVG(itens_pedido.item_total) AS mediaVenda'),
            )
                ->join("users","pedidos.user_id","=","users.id")
                    ->join("itens_pedido","pedidos.id","=","itens_pedido.item_pedido")
                        ->groupBy(
                            'users.name',
                            )
                            ->get();

        $mealType = DB::table('itens_pedido')
                ->select(
                    DB::raw(
                    "
                        CASE
                            WHEN menuitems.type_id = 1 THEN 'DRINKS'
                            WHEN menuitems.type_id = 2 THEN 'SOBREMESA'
                            WHEN menuitems.type_id = 3 THEN 'ENTRADA'
                            WHEN menuitems.type_id = 5 THEN 'PRINCIPAL'
                        ELSE '' END AS type
                    "
                    ),
                    "menuitems.item_name",
                    DB::raw("SUM(itens_pedido.item_total) typevenda"),
                                )
                    ->join('menuitems','itens_pedido.item_id','=','menuitems.id')
                        ->groupBy(
                            'menuitems.type_id',
                            'menuitems.item_name'
                            )
                            ->get();

        $itemsColection = DB::table('pedidos')
                            ->select(
                                'itens_pedido.item_emissao',
                                'menuitems.item_name',
                                'users.name',
                                DB::raw("SUM(itens_pedido.item_total) venda"),
                                DB::raw("SUM(item_quantidade) quantidade")
                            )
                                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                                    ->join('menuitems','itens_pedido.item_id','=','menuitems.id')
                                        ->join('users', 'pedidos.user_id', 'users.id')
                                            ->where('itens_pedido.item_delete', false)
                                                ->groupBy(
                                                    'itens_pedido.item_emissao',
                                                    'menuitems.item_name',
                                                    'users.name'
                                                )
                                                    ->orderBy('itens_pedido.item_emissao','desc')
                                                        ->get();

        return response()->json(
                [
                    'waiter'=>$waiter,
                    'type'=>$mealType,
                    'itemsCollection' => $itemsColection
                ]
            );
    }

    public function getGeneralStat(): JsonResponse
    {
        $query = DB::table('itens_pedido')
                ->select(
                    DB::raw('SUM(item_total) total'),
                    'item_emissao'
                )->groupBy('item_emissao')
                    ->get();
        return response()->json($query);
    }
}
