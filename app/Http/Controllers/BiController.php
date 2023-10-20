<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                            WHEN menuitems.type_id = 1 THEN 'ENTRADA'
                            WHEN menuitems.type_id = 2 THEN 'PRINCIPAL'
                        ELSE '' END AS type
                    "
                    ),
                    DB::raw("menuitems.item_name"),
                    DB::raw("SUM(itens_pedido.item_total) typevenda"),
                                )
                    ->join('menuitems','itens_pedido.item_id','=','menuitems.type_id')
                        ->groupBy(
                            'menuitems.type_id',
                            'menuitems.item_name'
                            )
                            ->get();

        $itemsColection = DB::table('itens_pedido')
                            ->select(
                                'itens_pedido.item_emissao',
                                'menuitems.item_name',
                                DB::raw("SUM(itens_pedido.item_total) venda"),
                                DB::raw("SUM(item_quantidade) quantidade")
                            )
                                ->join('menuitems','itens_pedido.item_id','=','menuitems.type_id')
                                    ->where('itens_pedido.item_delete', false)
                                        ->groupBy(
                                            'itens_pedido.item_emissao',
                                            'menuitems.item_name'
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
}
