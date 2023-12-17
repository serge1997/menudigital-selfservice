<?php

namespace App\Http\Controllers;

use App\Models\menuitems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\StockEntry;
use App\Models\Product;
use App\Models\Saldo;
use App\Models\Technicalfiche;
use DateTime;
use Exception;

class StockController extends Controller
{

    protected $supplier;
    protected $stockEntry;
    protected $product;

    public function __construct()
    {
        $this->supplier = new Supplier();
        $this->product = new Product();
    }

    public function StoreSupplier(Request $request): JsonResponse
    {
        $request->validate([
            "sup_name" => ["required"],
            "sup_tel" => ["required"],
        ],
        [
            "sup_name.required" => "name is required",
            "sup_tel.required" => "contact is required"
        ]);

        try {

            $data = $request->all();
            Supplier::create($data);
            return response()
                ->json("supplier created successfully",200);

        }catch(\Exception $e){
            return response()
                ->json("Action can't be realised", 400);
        }
    }

    public function getSupplier()
    {
        return response()
            ->json($this->supplier::select('id', 'sup_name')->get());
    }

    public function storeStockEntry(Request $request) :JsonResponse
    {
        $request->validate([
            'productID'=> ['required'],
            'supplierID'=> ['required',''],
            'quantity'=> ['required'],
            'unitCost'=> ['required']
        ],
        [
            'productID.required'=> 'product is required',
            'supplierID.required' => 'supplier is required',
            'quantity.required' => 'quantity is required',
            'unitCost.required' => 'cost is required'
        ]);

        $hoje = new DateTime();
        $hoje = $hoje->format("Y-m-d");

        $request->validate([
            'productID'=> ['required'],
            'supplierID' => ['required'],
            'quantity' => ['required'],
            'unitCost' => ['required'],
        ]);

        $produtData = Product::where('id', $request->productID)
            ->first();
        $maxemissao = DB::table('saldos')
            ->where('productID', $request->productID)
                ->max('emissao');
        $product = Saldo::select(DB::raw('MAX(emissao) emissao'), 'productID', 'saldoInicial', 'saldoFinal')
            ->where([
                    ['productID', $request->productID], ['emissao', $maxemissao]
                ])->groupby('emissao', 'productID', 'saldoInicial', 'saldoFinal')
                        ->first();

        $max_entry = DB::table('stock_entries')
            ->select(DB::raw('MAX(emissao) emissao'), 'productID')
                ->max('emissao');
        $entry_product = StockEntry::where([
                ['productID', $request->productID], ['emissao', $max_entry]
            ])->first();


        try {

            $data = $request->all();
            $entry = new StockEntry($data);
            $entry->totalCost = $request->unitCost * $request->quantity;
            $entry->emissao = $hoje;
            $entry->save();

            if ($product):
                if ($produtData->prod_unmed != "bt"):
                    DB::table('saldos')
                            ->where('productID', $request->productID)
                                ->update([
                                    'emissao' => $hoje,
                                    'saldoInicial' => $product->saldoInicial + ($request->quantity * $produtData->prod_contain),
                                    'saldoFinal' => $product->saldoFinal + ($request->quantity * $produtData->prod_contain)
                                ]);
                    return response()
                        ->json('Action registred successfully');
                else:
                    DB::table('saldos')
                        ->where('productID', $request->productID)
                            ->update([
                                'emissao' => $hoje,
                                'saldoInicial' => $product->saldoInicial + $request->quantity,
                                'saldoFinal' => $product->saldoFinal + $request->quantity
                            ]);
                    return response()
                        ->json('Action registred successfully');
                endif;
            else:
                if ($produtData->prod_unmed != "bt"):
                    $saldo = new Saldo();
                    $saldo->productID = $request->productID;
                    $saldo->emissao = $hoje;
                    $saldo->saldoInicial =  $request->quantity * $produtData->prod_contain;
                    $saldo->saldoFinal = $request->quantity * $produtData->prod_contain;
                    $saldo->save();
                else:
                    $saldo = new Saldo();
                    $saldo->productID = $request->productID;
                    $saldo->emissao = $hoje;
                    $saldo->saldoInicial =  $request->quantity;
                    $saldo->saldoFinal = $request->quantity;
                    $saldo->save();
                endif;
            endif;
            return response()
                ->json('Action registred successfully');

        }catch(\Exception $e){
            return response()
                ->json([
                        "status" => 400,
                        "msg" => "Action can't be completed"
                    ]);
        }

    }
    public function store_technical_fiche(Request $request) :JsonResponse
    {
        $request->validate([
            "itemID" => ["required"],
            "productID"=> ["required"],
            "quantity"=> ["required"]
        ],
        [
            "itemID.required"   => "menu item is required",
            "productID.required"  => "product is required",
            "quantity.required" => "quantity field is required"
        ]);

        $itemID = $request->itemID;
        $productID = $request->productID;
        $quantity = $request->quantity;
        try{

            $item_exist = Technicalfiche::where('itemID', $itemID)
                ->first();

            if ($item_exist){
                return response()->json("Technical fiche already exist", 400);
            }

            foreach ($productID as $key => $value):
                $stock = StockEntry::where('productID', $value)->first();
                $product = Product::where('id', $value)->first();

                if ($product->prod_unmed != "bt"):
                    $qty = $quantity[$key];
                    $fiche = new Technicalfiche();
                    $fiche->itemID = $itemID;
                    $fiche->productID = $value;
                    $fiche->quantity = $qty;
                    $fiche->cost = ($qty * $stock->unitCost) / $product->prod_contain;
                    $fiche->save();
                else:
                    $qty = $quantity[$key];
                    $fiche = new Technicalfiche();
                    $fiche->itemID = $itemID;
                    $fiche->productID = $value;
                    $fiche->quantity = $qty;
                    $fiche->cost = $stock->unitCost;
                    $fiche->save();
                endif;
            endforeach;

            return response()
                ->json("Technical fiche created successfully", 200);
        }catch(Exception $e){
            return response()->json("Action can't be completed", 422);
        }
    }

    public function get_stock_stat(): JsonResponse
    {
        $query = "
            SELECT
                MAX(st.emissao) emissao,
                max(st.unitCost) unitCost,
                st.productID,
                p.prod_name,
                sp.sup_name,
                sa.saldoFinal,
                p.prod_unmed
            FROM stock_entries st
                INNER JOIN saldos sa
                    ON sa.productID = st.productID
                INNER join products p
                    ON p.id = st.productID
                INNER JOIN suppliers sp
                    ON sp.id = st.supplierID
            GROUP BY
                st.productID,
                p.prod_name,
                sp.sup_name,
                p.prod_unmed,
                sa.saldoFinal
            HAVING MAX(st.emissao)
            ORDER BY p.prod_name
        ";
        return response()
            ->json(DB::select($query));
    }

    public function show_technical_fiche(int $id): JsonResponse
    {
        $item = menuitems::where('id', $id)->get();
        $fiche = DB::table('technicalfiches')
            ->select(
                'technicalfiches.itemID',
                'technicalfiches.productID',
                'menuitems.item_name',
                'products.prod_name',
                'technicalfiches.quantity',
                'technicalfiches.cost',
                'products.prod_unmed'
            )
                ->where('itemID', $id)
                    ->join('menuitems', 'technicalfiches.itemID', '=', 'menuitems.id')
                        ->join('products', 'technicalfiches.productID', '=', 'products.id')
                            ->get();

        return response()
            ->json($fiche);
    }

    public function Update_technical_fiche(Request $request)
    {
        /*
        $request->validate([
            'productID' => ['required'],
            'itemID' => ['required'],
            'quantity' => ['required']
        ]);
        $productID = $request->productID;
        $quantity = $request->quantity;
        $itemID = $request->itemID;

        foreach ($productID as $key => $value):
            $product = Product::where('id', $value)
                ->first();

            $cost = StockEntry::where('productID', $value)
                ->first();

            if ($product->prod_unmed != "bt"):
                DB::table('technicalfiches')
                    ->where([['itemID', $itemID],['productID', $value]])
                        ->update([
                            'quantity' => $quantity[$key],
                            'cost' => ($quantity[$key] * $cost->unitCost) / $product->prod_contain
                        ]);
                return response()
                    ->json("Action commited successffuly");
            endif;
            DB::table('technicalfiches')
                ->where([['itemID', $itemID],['productID', $value]])
                    ->update([
                        'quantity' => $quantity[$key],
                        'cost' => $cost->unitCost
                    ]);
        endforeach;

        return response()
            ->json("Action commited successffuly");
        //DB::table('technicalfiches')*/
    }

    public function get_inventory(): JsonResponse
    {
        $inventoty = "
            SELECT
            p.prod_name,
            CASE
                WHEN p.prod_unmed <> 'bt' THEN sa.saldoInicial / p.prod_contain
                ELSE
                sa.saldoInicial
            END saldoinicial,
            CASE
                WHEN p.prod_unmed <> 'bt' THEN  sa.saldoFinal / p.prod_contain
                ELSE
                sa.saldoFinal
            END saldofinal
        FROM saldos sa
        INNER JOIN products p
            ON sa.productID = p.id
        ";

        return response()->json(DB::select($inventoty));
    }

    public function resetSaldo(): JsonResponse
    {
        $query = "
            UPDATE saldos SET saldoInicial = saldoFinal
        ";

        DB::select($query);

        return response()->json("Journey reset successffully");
    }
}
