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
use App\Main\MenuItem\MenuItemRepositoryInterface;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;
use DateTime;
use Exception;

class MenuItemController extends Controller
{
    protected MenuItemRepositoryInterface $menuItemRepositoryInterface;

    public function __construct(MenuItemRepositoryInterface $menuItemRepositoryInterface)
    {
        $this->menuItemRepositoryInterface = $menuItemRepositoryInterface;
    }

    public function StoreMenuItem(StoreProductRequest $request): JsonResponse
    {
        try{
            $request->validated();
            $message = "Item salvou com sucesso";
            $this->menuItemRepositoryInterface->create($request);
            return response()->json($message);

        }catch(Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getAllMenu()
    {
        try {

            $menuResponse = $this->menuItemRepositoryInterface->getAll();
            return response()->json($menuResponse);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }


   /* public function setTableNumber(Request $request)
    {
        $values = $request->all();
        $cart = new Cart($values);
        $cart->tableNumber = $request->tableNumber;
        $cart->save();
    }*/

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

    public function findById($id)
    {
       try{
            $showMenuItemResponse = $this->menuItemRepositoryInterface->find($id);
            return response()->json($showMenuItemResponse);
       }catch(Exception $e){
        return response()->json($e->getMessage(), 500);
       }
    }

    public function showTechnicalFicheByMenuItemId($id, TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface): JsonResponse
    {
        try {

            $itemMenuResponse = $this->menuItemRepositoryInterface->find($id);
            $ficheReponse = $technicalFicheRepositoryInterface->findByItemId($id);

            return response()->json([
                'item' => $itemMenuResponse,
                'fiche' => $ficheReponse
            ]);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function searchMenuItem(Request $request)
    {
        try {

            $searchResult = $this->menuItemRepositoryInterface->search($request);
            return response()->json($searchResult);

        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function setRuptureAction($id)
    {
       try {
            $apiResponse = $this->menuItemRepositoryInterface->setRupture($id);
            return response()->json($apiResponse);

       }catch(Exception $e){
            return response()->json($e->getMessage());
       }
    }

    public function deleteItemOnStatus($id)
    {
        try{
            $message = "Item deletado com sucesso";
            $this->menuItemRepositoryInterface->delete($id);
            return response()->json($message);

        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function updatedMenuItem(Request $request)
    {
        try {

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

            $message = "Item editado com sucesso";
            $this->menuItemRepositoryInterface->update($request);
            return response()->json($message);

        }catch(\Exception $e){

           return response()->json($e->getMessage());
        }

    }

    public function getNewCart($table)
    {
        return response()->json(Cart::where('tableNumber', $table)->get());
    }
}
