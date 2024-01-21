<?php

namespace App\Http\Controllers;

use App\Main\Cart\CartRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartRepositoryInterface $cartRepositoryInterface;

    public function __construct(CartRepositoryInterface $cartRepositoryInterface)
    {
        $this->cartRepositoryInterface = $cartRepositoryInterface;
    }

    public function getCartItens($table): JsonResponse
    {
        try{

            $responseData = $this->cartRepositoryInterface->getCartItens($table);
            return response()->json([
                "items" => $responseData["items"],
                "total" => $responseData["total"],
                "options" => $responseData["options"]
            ]);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function AddItemdToCart($id, Request $request)
    {
        try {

            $this->cartRepositoryInterface->addToCart($id, $request);
        }catch(Exception $e){

            return response()->json($e->getMessage(), 500);
        }
    }

    public function incrementItemQuantity($id)
    {
        try{

            $response = $this->cartRepositoryInterface->addQuantity($id);
            return response()->json([
                "quantity" => $response["quantity"],
                "total"    => $response["total"]
            ]);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function decrementItemQuantity($id)
    {
        try {

            $response = $this->cartRepositoryInterface->reduceQuantity($id);
            return response()->json([
                "quantity" => $response["quantity"],
                "total"    => $response["total"]
            ]);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteItemFromCart($cartId, $table)
    {
        try{
            $this->cartRepositoryInterface->deleteFromCart($cartId, $table);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
