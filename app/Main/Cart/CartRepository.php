<?php
namespace App\Main\Cart;


use App\Main\Cart\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Menuitems;
use App\Models\Cart;
use App\Models\MealType;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Http\Resources\CartResource;
use App\Main\MenuItem\MenuItemRepositoryInterface;

class CartRepository implements CartRepositoryInterface
{
    public MenuItemRepositoryInterface $menuItemRepositoryInterface;
    public function __construct(
        MenuItemRepositoryInterface $menuItemRepositoryInterface
    )
    {
        $this->menuItemRepositoryInterface = $menuItemRepositoryInterface;
    }

    public function addToCart($id, $request)
    {
        $menuitem = $this->menuItemRepositoryInterface->find($id);

        $cart = new Cart();
        $cart->item_id = $id;
        $cart->tableNumber = $request->tableNumber;
        $cart->unit_price = $menuitem->item_price;
        $cart->total = $menuitem->item_price;


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
                $cart->save();
           }
        return CartResource::collection(
            Cart::where('tableNumber', $request->tableNumber)
                ->get()
        );
    }

    public function addQuantity($id): array
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

        return [
            'quantity' => $qty->quantity,
            'total' => $total->total
        ];
    }

    public function reduceQuantity($id): array
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
        return [
            'quantity' => $qty->quantity,
            'total' => $total->total
        ];
    }

    public function deleteFromCart($id)
    {
        Cart::find($id)->delete();
    }

    public function getCartItens($table): array
    {
        global $options;
        $items = Cart::select(
            'carts.id AS cartID',
            'menuitems.item_name',
            'menuitems.item_image',
            'menuitems.item_desc',
            'carts.item_id',
            'carts.unit_price',
            'menuitems.type_id',
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
            $options = Option::where("type_id", $item->type_id)->get();
        }

        return [
            'items' => CartResource::collection(Cart::where('tableNumber', $table)->get()),
            'total' => $total,
            'options' => $options
        ];
    }
}
