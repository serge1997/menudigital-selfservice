<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Models\Menuitems;
use App\Models\Cart;
use App\Models\MealType;
use DateTime;

class MenuItemController extends Controller
{
    public function getMealType()
    {
        return response()->json([
            'type' => DB::table('menu_mealtype')->select('id_type', 'desc_type')
                        ->get()
        ]);
    }

    public function StoreMenuItem(StoreProductRequest $request) {

        $request->validated();
        
        $values = $request->all();
        $item = new Menuitems($values);

        try{

            DB::beginTransaction();
            $item -> save();
            DB::commit();

            return response()->json([
                "success" => "Item Salvou com sucesso"
            ]);

        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                "error" => "O item não pode ser salvou",
                "status" => 500
            ]);
        }
    }

    public function SaveType(Request $request)
    {
        $type = new MealType();
        $type->desc_type = $request->desc_type;

        if($request->hasFile('foto_type') && $request->file('foto_type')->isValid()){
            $foto = $request->foto_type;
            $extension = $foto->extension();
            $fotoname = md5($foto->getClientOriginalName(). strtotime("now")). ".". $extension;
            $foto->move(public_path('img/type'), $fotoname);
            $type->foto_type = $fotoname;
        }

        $type->save();

        return response()->json("Item casdastrado com successo");
    }

    public function getMenu()
    {
        $items = DB::table("menuitems")->select('*')
            ->join("menu_mealtype", "menuitems.type_id", "=", "menu_mealtype.id_type")
            ->get();

        return response()->json([
            'items' => $items
        ]);
    }

    public function ShowCart($id, $table)
    {
        $items = DB::table("carts")->select("menuitems.id", "menuitems.item_name", "menuitems.item_price", "menuitems.item_desc", "carts.quantity", "carts.total")
            ->join("menuitems", "carts.item_id", "=", "menuitems.id")
            ->where([["menuitems.id", "=" , $id], ["carts.tableNumber", "=", $table]])
            ->get();

        $options = DB::table("menuitems")->select("options.option_name")
            ->join("options", "menuitems.type_id", "=", "options.type_id")
            ->where("menuitems.id", $id)
            ->get();


        return response()->json([
            'cartitem' => $items,
            'options' => $options
        ]);
    }

    public function getMenuType()
    {
        $type = DB::table("menu_mealtype")
            ->get();

        return response()->json($type);
    }

    public function getItemOfType($id_type)
    {
        $items = DB::table("menuitems")->select('*')
            ->join("menu_mealtype", "menuitems.type_id", "=", "menu_mealtype.id_type")
            ->where("menuitems.type_id", $id_type)
            ->get();

        return response()->json(([
            'items' => $items
        ]));
    }

    public function setTableNumber(Request $request)
    {
        $values = $request->all();
        $cart = new Cart($values);
        $cart->tableNumber = $request->tableNumber;
        $cart->save();
    }
    public function addToCart($id, Request $request)
    {
        
        $price = Menuitems::select('item_price')
            ->where('id', $id)
            ->first();
        
        $cart = new Cart();
        $cart->item_id = $id;
        $cart->tableNumber = $request->tableNumber;
        $cart->unit_price = $price->item_price;
        $cart->total = $price->item_price;
        //$cart->save();
        
        try {

            $unitPrice = DB::table('carts')->select('unit_price')
                ->where("item_id", $id)
                ->first();

            $checkProductExist = Cart::select('item_id')
                ->where([["item_id", "=", $id], ["tableNumber", "=", $request->tableNumber]])
                ->first();
           

            $quantidade = DB::table('carts')->select('quantity')
                ->where([["item_id", "=", $id], ["tableNumber", "=", $request->tableNumber]])
                ->first();

           if ($checkProductExist) {

                DB::table('carts')->where([['item_id', '=', $id], ['tableNumber', '=', $request->tableNumber]])
                    ->update([
                        'quantity' => $quantidade->quantity += 1,
                        'total' => $unitPrice->unit_price * $quantidade->quantity 
                    ]);
            
           }else {

                DB::beginTransaction();
                $cart->save();
                DB::commit();
           }

        }catch(\Exception $e) {
            return response()->json($e);
        }

        
        
    
    }

    public function SetCartOptions($id, Request $request)
    {
        DB::table('carts')->where([['tableNumber', '=', $request->tableNumber] , ['item_id', '=' ,$id]])
            ->update([
                'options' => $request->options,
                'comments' => $request->comments
            ]);
    }

    public function checkCart($checkTable)
    {
        $cart = Cart::where('tableNumber', $checkTable)->first();

        if ($cart) {
            return response()->json(True);
        }else {
            return response()->json(False);
        }
    }

    public function AddQuantity($id, $table)
    {
        $item = Cart::find($id);

        $quantidade = DB::table('carts')->select('quantity')
            ->where([["item_id", "=", $id], ["tableNumber", "=", $table]])
            ->first();

        $unitPrice = DB::table('carts')->select('unit_price')
            ->where("item_id", $id)
            ->first();

        if ($quantidade->quantity <= 8) {
            DB::table('carts')->where([["item_id", "=", $id], ["tableNumber", "=", $table]])
            ->update([
                'quantity' => $quantidade->quantity += 1,
                'total' => $unitPrice->unit_price * $quantidade->quantity
            ]);
        }

        $qty = DB::table('carts')->select('quantity')
            ->where([['item_id', '=', $id], ['tableNumber', '=', $table]])
            ->first();

        $total = DB::table('carts')->select('total')
            ->where([["item_id", "=", $id], ["tableNumber", "=", $table]])
            ->first();

        return response()->json([
            'quantity' => $qty->quantity,
            'total' => $total->total
        ]);
    }

    public function ReduceQuantity($id, $table)
    {
        $quantidade = DB::table('carts')->select('quantity')
            ->where([["item_id", "=", $id], ["tableNumber", "=", $table]])
            ->first();

        $unitPrice = DB::table('carts')->select('unit_price')
            ->where("item_id", $id)
            ->first();

        $oldTotal = DB::table('carts')->select('total')
            ->where("item_id", $id)
            ->first();

        if ($quantidade->quantity > 1) {

            DB::table('carts')->where([["item_id", "=", $id], ["tableNumber", "=", $table]])
            ->update([
                'quantity' => $quantidade->quantity -= 1,
                'total' => $oldTotal->total - $unitPrice->unit_price
            ]);
        }

        $qty = DB::table('carts')->select('quantity')
            ->where([['item_id', '=', $id], ['tableNumber', '=', $table]])
            ->first();

        $total = DB::table('carts')->select('total')
            ->where([["item_id", "=", $id], ["tableNumber", "=", $table]])
            ->first();

        return response()->json([
            'quantity' => $qty->quantity,
            'total' => $total->total
        ]);
    }

    public function CustomerFinalCart($table)
    {
        $items = DB::table('carts')->select(
                'carts.id AS cartID',
                'menuitems.item_name', 
                'menuitems.item_image',
                'menuitems.item_desc',
                'carts.quantity',
                'carts.options',
                'carts.quantity',
                'carts.tableNumber', 
                'carts.total'
            )
            ->join('menuitems', 'carts.item_id', '=', 'menuitems.id')
            ->where('tableNumber', $table)
            ->get();

        $total = DB::table('carts')
            ->where('tableNumber', $table)
            ->sum('total');
            

        return response()->json([
            'items' => $items,
            'total' => $total
        ]);
    }

    public function DeleteFromCart($cartID, $table)
    {
        DB::table('carts')->where('id', $cartID)
            ->delete();

        $items = DB::table("carts")->select("menuitems.id", "menuitems.item_name", "menuitems.item_price", "menuitems.item_desc", "carts.quantity", "carts.total")
            ->join("menuitems", "carts.item_id", "=", "menuitems.id")
            ->where([["menuitems.id", "=" , $cartID], ["carts.tableNumber", "=", $table]])
            ->get();

        return response()->json($items);
    }


    public function getTable()
    {
        $table = DB::table('tablenumber')->select('tablenumber.table')
            ->whereNotIn('id', function($query) {
                $hoje = new DateTime();
                $hoje = $hoje->format("Y-m-d");
                $query->select('ped_tableNumber')
                    ->from('pedidos')
                        ->where('ped_emissao', $hoje)
                            ->where('status_id', '=', 6);
            })
                ->get();

        return response($table);
    }

    public function show($id)
    {
        return response()->json(Menuitems::where('id', $id)->get());
    }

}
