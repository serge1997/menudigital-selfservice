<?php

namespace App\Http\Controllers;

use App\Http\Services\UserInstance;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Models\Menuitems;
use App\Models\Cart;
use App\Models\MealType;
use App\Http\Services\Menuitem\MenuItemRepository;
use DateTime;

class MenuItemController extends Controller
{
    public $items;
    protected MenuItemRepository $menuItemRepository;

    public function __construct(MenuItemRepository $menuItemRepository)
    {
        $this->items = new MenuItems();
        $this->menuItemRepository = $menuItemRepository;
    }
    public function getMealType()
    {
        return response()->json([
            'type' => DB::table('menu_mealtype')
                ->select('id_type', 'desc_type')
                     ->get()
        ]);

    }

    public function StoreMenuItem(StoreProductRequest $request): JsonResponse
    {
        $request->validated();
        $user_id = $request->session()->get('auth-vue');
        $values = $request->all();
        try{
            $this->menuItemRepository->beforeSave($request->item_name);
            foreach (UserInstance::get_user_roles($user_id) as $item_role):
                if ($item_role->role_id === Role::MANAGER):
                    $item = new Menuitems($values);
                    $item -> save();
                    return response()->json("Item Salvou com sucesso", 200);
                 endif;
            endforeach;
            return response()->json("User don't have permission", 400);
        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(),400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @use Role $role
     * make a migration fresh to put type desc not null
     */
    public function SaveType(Request $request) :JsonResponse
    {
        $request->validate([
            'desc_type' => ['required']
        ]);

        $type = new MealType();
        $type->desc_type = $request->desc_type;

        if($request->hasFile('foto_type') && $request->file('foto_type')->isValid()){
            $foto = $request->foto_type;
            $extension = $foto->extension();
            $fotoname = md5($foto->getClientOriginalName(). strtotime("now")). ".". $extension;
            $foto->move(public_path('img/type'), $fotoname);
            $type->foto_type = $fotoname;
        }
        try{
            $this->menuItemRepository->beforeSaveMealType($request->desc_type);

            $auth = $request->session()->get('auth-vue');
            foreach (UserInstance::get_user_roles($auth) as $menu):
                if ($menu->role_id === Role::MANAGER):
                    $type->save();
                    return response()->json("Item casdastrado com successo", 200);
                endif;
            endforeach;
            return response()->json("You dont have permission", 500);
        }catch (\Exception $e){
            return response()->json($e->getMessage(), 400);
        }



    }

    public function getMenu()
    {
        $items = DB::table("menuitems")->select('*')
            ->join("menu_mealtype", "menuitems.type_id", "=", "menu_mealtype.id_type")
                ->where('menuitems.item_status', '=', true)
                    ->orderBy('menuitems.item_name')
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

    public function getMenuType(): JsonResponse
    {
        $type = DB::table("menu_mealtype")
            ->select(
                    "menu_mealtype.id_type",
                    "menu_mealtype.desc_type",
                    "menu_mealtype.foto_type",
                    DB::raw("COUNT(menuitems.id) as item_qty")
                )
                ->join('menuitems', 'menu_mealtype.id_type', '=', 'menuitems.type_id')
                    ->where('menuitems.item_status', '=', true)
                        ->groupby(
                            "menu_mealtype.id_type",
                            "menu_mealtype.desc_type",
                            "menu_mealtype.foto_type"
                        )
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

    public function AddQuantity($id): JsonResponse
    {
        $cart = Cart::where('id', $id)->first();
        if ($cart->quantity <= 8) {
            DB::table('carts')->where("id", "=", $id)
            ->update([
                'quantity' => $cart->quantity += 1,
                'total' => $cart->unit_price * $cart->quantity
            ]);
        }

        $qty = DB::table('carts')->select('quantity')
            ->where('id', '=', $id)
            ->first();

        $total = DB::table('carts')->select('total')
            ->where("id", "=", $id)
            ->first();

        return response()->json([
            'quantity' => $qty->quantity,
            'total' => $total->total
        ]);
    }

    public function ReduceQuantity($id): JsonResponse
    {
        $cart = Cart::where('id', $id)->first();
        $oldTotal = DB::table('carts')->select('total')
            ->where("id", $id)
            ->first();

        if ($cart->quantity > 1) {

            DB::table('carts')->where("id", "=", $id)
            ->update([
                'quantity' => $cart->quantity -= 1,
                'total' => $oldTotal->total - $cart->unit_price
            ]);
        }
        $qty = DB::table('carts')->select('quantity')
            ->where('id', '=', $id)
            ->first();

        $total = DB::table('carts')->select('total')
            ->where("id", "=", $id)
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
                'carts.item_id',
                'carts.unit_price',
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

        foreach ($items as $item){
            $this->options = DB::table("menuitems")->select("options.option_name")
                ->join("options", "menuitems.type_id", "=", "options.type_id")
                ->where("menuitems.id", $item->item_id)
                ->get();
        }



        return response()->json([
            'items' => $items,
            'total' => $total,
            //'options' => $this->options
        ]);
    }

    public function DeleteFromCart($cartID, $table) :JsonResponse
    {
        DB::table('carts')->where('id', $cartID)
            ->delete();

        $items = DB::table("carts")->select("menuitems.id", "menuitems.item_name", "menuitems.item_price", "menuitems.item_desc", "carts.quantity", "carts.total")
            ->join("menuitems", "carts.item_id", "=", "menuitems.id")
            ->where([["menuitems.id", "=" , $cartID], ["carts.tableNumber", "=", $table]])
            ->get();

        return response()->json($items);
    }


    public function getTable(): JsonResponse
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

        return response()->json($table);
    }

    public function show($id)
    {
        $item = Menuitems::where('id', $id)->get();
        $fiche = DB::table('technicalfiches')
            ->select(
                '*'
            )
                ->join('products', 'technicalfiches.productID', '=', 'products.id')
                    ->where('itemID', $id)
                        ->get();

        return response()->json(
            [
                'item' => $item,
                'fiche' => $fiche
            ]
        );
    }

    public function getItemForEdit($id)
    {
        $otherItems = Menuitems::where('id', '=', $id)->get();
        return response()->json($otherItems);
    }

    public function SetRupture($id)
    {
        $item = Menuitems::where('id','=', $id)->first();
        $id = filter_var($id, FILTER_VALIDATE_INT);

        DB::table('menuitems')
            ->where('id', $id)
                ->update([
                    'item_rupture' => !$item->item_rupture
                ]);
    }

    public function ToDelete($id): JsonResponse
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);

        DB::table('menuitems')
            ->where('id', $id)
                ->update([
                    'item_status' => false
                ]);

        return response()->json("Item deletado com sucesso");
    }

    public function updatedMenuItem(Request $request)
    {
        $request->validate([
            "item_name" => ["required"],
            "item_price" => ["required"],
            "item_desc" => ["required"]
        ],
        [
            "item_name.required" => "item name required",
            "item_price.required" => "item price required",
            "item_desc" => "item description is required"
        ]);

        $item_name = $request->item_name;
        $item_price = $request->item_price;
        $item_id = $request->item_id;
        $item_desc = $request->item_desc;

        try {

            DB::table("menuitems")
            ->where("id", $item_id)
                ->update([
                    "item_name" => $item_name,
                    "item_desc" => $item_desc,
                    "item_price" => $item_price
                ]);
            return response()
                ->json("Item editado com sucesso");
        }catch(\Exception $e){
            return response()
                ->json("Item não pode ser editado, tente novamente");
        }

    }

    public function getNewCart($table)
    {
        return response()->json(Cart::where('tableNumber', $table)->get());
    }
}
