<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockEntry;
use App\Models\ItensPedido;
use DateTime;
use DateInterval;
use App\Http\Services\Util\Util;
use Exception;

class BiController extends Controller
{
    /**
     * @throws \Exception
     */
    public function waiterStat(Request $request){

        $start = substr($request->start, 0, 10);
        $end = substr($request->end, 0, 10);
        $user = $request->user;
        $item = $request->item;
        $today = new DateTime();
        $today = $today->format('Y-m-d');

        $todayForLastMonth = new DateTime();
        $lastMonth = $todayForLastMonth->sub(new DateInterval('P1M'));
        $lastMonth = $lastMonth->format('Y-m');

        $todayThisMonth = new DateTime();
        $thisMonth = $todayThisMonth->format('Y-m');

        //where query
        $user_where = "";
        $itemsColection_where = "";
        $mealType_where = "";
        $thismonth_where = "";
        $lastmonth_where = "";
        $couverts_where = "pedidos.ped_delete = '0' ";
        $couverts = "";

        if ($user){
            $user_where .= "pedidos.status_id <> '6' AND pedidos.user_id = '{$user}' ";
            $itemsColection_where .= "itens_pedido.item_delete = '0' AND pedidos.status_id <> '6' AND pedidos.user_id = '{$user}' ";
            $mealType_where .= "pedidos.status_id <> '6' AND pedidos.user_id = '{$user}' ";
            $thismonth_where .= "WHERE pd.status_id <> '6' AND item_emissao LIKE '%".$thisMonth."%' AND pd.user_id = '{$user}' ";
            $lastmonth_where .= "WHERE pd.status_id <> '6' AND item_emissao LIKE '%".$lastMonth."%' AND pd.user_id = '{$user}' ";
            $couverts_where .= "AND user_id = '{$user}' ";
        }else{
            $user_where .= "pedidos.status_id <> '6' ";
            $itemsColection_where .= "itens_pedido.item_delete = '0' AND pedidos.status_id <> '6' ";
            $mealType_where .= "pedidos.status_id <> '6' ";
            $lastmonth_where .= "WHERE pd.status_id <> '6' AND item_emissao LIKE '%".$lastMonth."%' ";
            $thismonth_where .= "WHERE pd.status_id <> '6' AND item_emissao LIKE '%".$thisMonth."%' ";
        }
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $endDate = $endDate->format('Y-m-d');
        $startDate = $startDate->format('Y-m-d');

        if ($item){
            $user_where .= "AND itens_pedido.item_id = '{$item}'";
            $itemsColection_where .= "AND itens_pedido.item_id = '{$item}'";
            $mealType_where .= "AND itens_pedido.item_id = '{$item}'";
            $thismonth_where .= "AND itens.item_id = '{$item}'";
            $lastmonth_where .= "AND itens.item_id = '{$item}'";
            $couverts_where .= "AND itens.item_id = '{$item}' ";
            $couverts .= Pedido::join('itens_pedido as itens', 'pedidos.id', '=', 'itens.item_pedido')
                ->whereRaw($couverts_where)
                    ->whereBetween('pedidos.ped_emissao', [$startDate, $endDate])
                        ->sum('pedidos.ped_customer_quantity');
        }else {
            $couverts .= Pedido::whereRaw($couverts_where)
                ->where('pedidos.status_id', '<>', '6')
                    ->whereBetween('pedidos.ped_emissao', [$startDate, $endDate])
                        ->sum('pedidos.ped_customer_quantity');
        }

        //last month sell
        $sellLastMonthQuery = "SELECT SUM(itens.item_total) AS total FROM itens_pedido AS itens ";
        $sellLastMonthQuery.= "INNER JOIN pedidos AS pd ON pd.id = itens.item_pedido ";
        $sellLastMonthQuery .= $lastmonth_where;
        $sellLastMontValue = DB::select($sellLastMonthQuery);

        //this month sell
        $sellThisMonthQuery = "SELECT SUM(itens.item_total) AS total FROM itens_pedido AS itens ";
        $sellThisMonthQuery.= "INNER JOIN pedidos AS pd ON pd.id = itens.item_pedido ";
        $sellThisMonthQuery .= $thismonth_where;
        $sellThisMonthValue = DB::select($sellThisMonthQuery);

        //sell today
        $sellingToday = DB::table('itens_pedido')
            ->select(DB::raw('SUM(itens_pedido.item_total) AS totalDay'))
                ->join('pedidos', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                    ->where('pedidos.ped_emissao', $today)
                        ->whereRaw($user_where)
                            ->get();

        $waiter = DB::table("pedidos")
            ->select(
                'users.name',
                DB::raw('SUM(itens_pedido.item_total) AS venda'),
            )
                ->join("users","pedidos.user_id","=","users.id")
                    ->join("itens_pedido","pedidos.id","=","itens_pedido.item_pedido")
                        ->whereBetween('pedidos.ped_emissao', [$startDate, $endDate])
                            ->whereRaw($user_where)
                                ->groupBy(
                                    'users.name',
                                    )
                                    ->get();

        $mealType = DB::table('itens_pedido')
                ->select(
                    DB::raw(
                    "
                        CASE
                            WHEN menuitems.type_id = 2 THEN 'ENTRADA'
                            WHEN menuitems.type_id = 5 THEN 'VINHO'
                            WHEN menuitems.type_id = 4 THEN 'PRINCIPAL'
                            WHEN menuitems.type_id = 1 THEN 'DRINKS'
                            WHEN menuitems.type_id = 6 THEN 'SOBREMESA'
                            WHEN menuitems.type_id = 3 THEN 'FASTFOOD'
                        ELSE '' END AS type
                    "
                    ),
                    "menuitems.item_name",
                    DB::raw("SUM(itens_pedido.item_total) typevenda"),
                                )
                    ->join('menuitems','itens_pedido.item_id','=','menuitems.id')
                        ->join('pedidos', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                            ->join('menu_mealtype AS mt', 'menuitems.type_id', '=', 'mt.id_type')
                                ->whereBetween('itens_pedido.item_emissao', [$startDate, $endDate])
                                    ->whereRaw($mealType_where)
                                        ->whereNotNull('menuitems.type_id')
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
                                            ->whereRaw($itemsColection_where)
                                                ->whereBetween('itens_pedido.item_emissao', [$startDate, $endDate])
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
                    'itemsCollection' => $itemsColection,
                    'lastMonth' => $sellLastMontValue,
                    'thisMonth' => $sellThisMonthValue,
                    'totalDay' => $sellingToday,
                    'couverts' => $couverts
                ]
            );
    }

    /**
     * @throws \Exception
     */
    public function getGeneralStat(Request $request): JsonResponse
    {
        $start = substr($request->start, 0, 10);
        $end = substr($request->end, 0, 10);
        $user = $request->user;
        $item = $request->item;

        $where_close = "";
        if ($user){
            $where_close .= "pedidos.status_id <> '6' AND pedidos.user_id = '{$user}' ";
        }else{
            $where_close .= "pedidos.status_id <> '6' ";
        }
        if($item){
            $where_close .= "AND itens_pedido.item_id = '{$item}'";
        }

        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $endDate = $endDate->format('Y-m-d');
        $startDate = $startDate->format('Y-m-d');
        $query = DB::table('itens_pedido')
                ->select(
                    DB::raw('SUM(item_total) total'),
                    'item_emissao'
                )
                    ->join('pedidos', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                            ->whereBetween('item_emissao', [$startDate, $endDate])
                                ->whereRaw($where_close)
                                    ->groupBy('item_emissao')
                                        ->get();

        return response()->json($query);
    }

    public function costQuery(): JsonResponse
    {
        //analyse custo produto / fornecedor
        $cost = StockEntry::select(
            'stock_entries.productID AS product_id',
            'stock_entries.requisition_id',
            'products.prod_name',
            'suppliers.sup_name',
            DB::raw('SUM(stock_entries.totalCost) AS totalCost'),
            DB::raw('SUM(stock_entries.quantity) AS quantity'),
            DB::raw('TRUNCATE(AVG(unitCost), 2) AS cost'),
            DB::raw("CONCAT(SUBSTRING(MONTHNAME(stock_entries.emissao),1, 3), '/' ,YEAR(stock_entries.emissao)) AS period")
        )
        ->join('products', 'stock_entries.productID', '=', 'products.id')
            ->join('suppliers', 'stock_entries.supplierID', 'suppliers.id')
                ->where('stock_entries.is_delete', false)
                    ->groupBy(
                        'stock_entries.productID',
                        'products.prod_name',
                        'suppliers.sup_name',
                        'stock_entries.requisition_id',
                        DB::raw("CONCAT(SUBSTRING(MONTHNAME(stock_entries.emissao),1, 3), '/',YEAR(stock_entries.emissao))")
                    )
                    ->orderBy('suppliers.sup_name')
                        ->get();

        //query gastos em fornecedor.

        $supplier = StockEntry::select(
            'suppliers.sup_name',
            DB::raw('SUM(stock_entries.totalCost) AS totalCost'),
            DB::raw('TRUNCATE(SUM(stock_entries.totalCost) * 100 / (SELECT SUM(st.totalCost) from stock_entries AS st), 2)  AS percent'),
            DB::raw('SUM(stock_entries.quantity) AS quantity'),
        )
        ->join('suppliers', 'stock_entries.supplierID', '=', 'suppliers.id')
            ->where('stock_entries.is_delete', false)
                ->groupBy(
                    'suppliers.sup_name'
                )->get();

        return response()->json([
            'cost' => $cost,
            'supCost' => $supplier
        ]);
    }

    public function filterCostIntelligence(Request $params): JsonResponse
    {
        try{

            $condition_like = "st.is_delete = '0' AND ";
            if ($params->prodName) {
                $condition_like .= "pr.prod_name LIKE '%".$params->prodName."%' OR ";
                $condition_like .= "supp.sup_name LIKE '%".$params->prodName."%' AND ";
            }
            if ($params->year) {
                $condition_like .= "SUBSTRING(st.emissao, 1, 4) LIKE '%".$params->year."%' AND ";
            }
            if ($params->month){
                $condition_like .= "SUBSTRING(MONTHNAME(st.emissao), 1, 4) LIKE '%".$params->month."%' AND ";
            }

            $condition_like .= "supp.is_delete <> 1 AND st.is_delete <> 1";
            //var_dump($condition_like); die;
            $query = "SELECT
                st.productID AS product_id,
                CASE
                    WHEN pr.prod_unmed = 'g' THEN CONCAT(pr.prod_name, '/ ', pr.prod_unmed)
                ELSE
                   pr.prod_name
                END prod_name,
                supp.sup_name,
                st.requisition_id,
                SUM(st.totalCost) AS totalCost,
                SUM(st.quantity) AS quantity,
                TRUNCATE(AVG(st.unitCost), 2) AS cost,
                CONCAT(SUBSTRING(MONTHNAME(st.emissao),1, 3), '/',YEAR(st.emissao)) AS period
            FROM stock_entries AS st
            INNER JOIN products AS pr
                ON st.productID = pr.id
            INNER JOIN suppliers AS supp
                ON st.supplierID = supp.id
            WHERE {$condition_like}
            GROUP BY
                    st.productID,
                    pr.prod_name,
                    supp.sup_name,
                    st.requisition_id,
                    CONCAT(SUBSTRING(MONTHNAME(st.emissao),1, 3), '/',YEAR(st.emissao)),
                    pr.prod_unmed
            ORDER BY supp.sup_name";

            $supplier = StockEntry::select(
                'supp.sup_name',
                DB::raw('SUM(stock_entries.totalCost) AS totalCost'),
                DB::raw("TRUNCATE(SUM(stock_entries.totalCost) * 100 / (SELECT SUM(st.totalCost)
                    from stock_entries AS st WHERE MONTHNAME(st.emissao) LIKE '%{$params->month}%'
                    AND SUBSTRING(st.emissao, 1, 4) LIKE '%{$params->year}%'), 2)  AS percent"),
                DB::raw('SUM(stock_entries.quantity) AS quantity'),
            )
            ->join('suppliers as supp', 'stock_entries.supplierID', '=', 'supp.id')
                ->join('products as pr', 'stock_entries.productID', 'pr.id')
                    ->where('stock_entries.is_delete', false)
                        ->whereRaw(
                            "MONTHNAME(stock_entries.emissao) LIKE '%{$params->month}%' AND SUBSTRING(stock_entries.emissao, 1, 4) LIKE '%{$params->year}%'
                            AND pr.prod_name LIKE '%".$params->prodName."%' OR supp.sup_name LIKE '%.$params->prodName.%'
                            ")
                            ->groupBy(
                                'supp.sup_name'
                            )
                            ->get();

            return response()->json([
                'cost' => DB::select($query),
                'supCost' => $supplier
            ]);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
