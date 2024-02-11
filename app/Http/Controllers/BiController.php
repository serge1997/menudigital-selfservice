<?php

namespace App\Http\Controllers;

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
    public function waiterStat($start, $end){

        $todayForLastMonth = new DateTime();
        $todayThisMonth = new DateTime();
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $endDate = $endDate->format('Y-m-d');
        $startDate = $startDate->format('Y-m-d');

        //last month sell
        $lastMonth = $todayForLastMonth->sub(new DateInterval('P1M'));
        $lastMonth = $lastMonth->format('Y-m');

        $sellLastMonthQuery = "SELECT SUM(itens.item_total) AS total FROM itens_pedido AS itens ";
        $sellLastMonthQuery.= "INNER JOIN pedidos AS pd ON pd.id = itens.item_pedido ";
        $sellLastMonthQuery .= "WHERE pd.status_id <> 6 AND item_emissao LIKE '%".$lastMonth."%'";
        $sellLastMontValue = DB::select($sellLastMonthQuery);

        //this month sell
        $thisMonth = $todayThisMonth->format('Y-m');
        $sellThisMonthQuery = "SELECT SUM(itens.item_total) AS total FROM itens_pedido AS itens ";
        $sellThisMonthQuery.= "INNER JOIN pedidos AS pd ON pd.id = itens.item_pedido ";
        $sellThisMonthQuery .= "WHERE pd.status_id <> 6 AND item_emissao LIKE '%".$thisMonth."%'";
        $sellThisMonthValue = DB::select($sellThisMonthQuery);

        //sell today
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $sellingToday = DB::table('itens_pedido')
            ->select(DB::raw('SUM(itens_pedido.item_total) AS totalDay'))
                ->join('pedidos', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                    ->where([['status_id', '<>', 6], ['pedidos.ped_emissao', '=' ,$today]])
                        ->get();

        $waiter = DB::table("pedidos")
            ->select(
                'users.name',
                DB::raw('SUM(itens_pedido.item_total) AS venda'),
                DB::raw('AVG(itens_pedido.item_total) AS mediaVenda'),
            )
                ->join("users","pedidos.user_id","=","users.id")
                    ->join("itens_pedido","pedidos.id","=","itens_pedido.item_pedido")
                        ->whereBetween('itens_pedido.item_emissao', [$startDate, $endDate])
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
                            WHEN menuitems.type_id = 3 THEN 'VINHO'
                            WHEN menuitems.type_id = 4 THEN 'PRINCIPAL'
                            WHEN menuitems.type_id = 1 THEN 'DRINKS'
                            WHEN menuitems.type_id = 5 THEN 'SOBREMESA'
                        ELSE '' END AS type
                    "
                    ),
                    "menuitems.item_name",
                    DB::raw("SUM(itens_pedido.item_total) typevenda"),
                                )
                    ->join('menuitems','itens_pedido.item_id','=','menuitems.id')
                    ->join('pedidos', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                        ->whereBetween('itens_pedido.item_emissao', [$startDate, $endDate])
                        ->where('pedidos.status_id', '<>', 6)
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
                                            ->where([['itens_pedido.item_delete', false],['pedidos.status_id', '<>', 6]])
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
                    'totalDay' => $sellingToday
                ]
            );
    }

    /**
     * @throws \Exception
     */
    public function getGeneralStat($start, $end): JsonResponse
    {
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
                                ->where('pedidos.status_id', '<>', 6)
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
            ->groupBy(
                'suppliers.sup_name'
            )
                ->get();

        return response()->json([
            'cost' => $cost,
            'supCost' => $supplier
        ]);
    }

    public function filterCostIntelligence(Request $params): JsonResponse
    {
        try{

            $condition_like = "";
            if ($params->prodName) {
                $condition_like .= "pr.prod_name LIKE '%".$params->prodName."%' AND ";
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
                pr.prod_name,
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
                    CONCAT(SUBSTRING(MONTHNAME(st.emissao),1, 3), '/',YEAR(st.emissao))
            ORDER BY supp.sup_name";

            $supplier = StockEntry::select(
                'suppliers.sup_name',
                DB::raw('SUM(stock_entries.totalCost) AS totalCost'),
                DB::raw("TRUNCATE(SUM(stock_entries.totalCost) * 100 / (SELECT SUM(st.totalCost)
                    from stock_entries AS st WHERE MONTHNAME(st.emissao) LIKE '%{$params->month}%'
                    AND SUBSTRING(st.emissao, 1, 4) LIKE '%{$params->year}%'), 2)  AS percent"),
                DB::raw('SUM(stock_entries.quantity) AS quantity'),
            )
            ->join('suppliers', 'stock_entries.supplierID', '=', 'suppliers.id')
                ->whereRaw("MONTHNAME(stock_entries.emissao) LIKE '%{$params->month}%' AND SUBSTRING(stock_entries.emissao, 1, 4) LIKE '%{$params->year}%'")
                    ->groupBy(
                        'suppliers.sup_name'
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
