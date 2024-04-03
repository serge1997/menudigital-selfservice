<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TechnicalFicheController extends Controller
{
    protected TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface;

    public function __construct(TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface)
    {
        $this->technicalFicheRepositoryInterface = $technicalFicheRepositoryInterface;
    }

    public function createAction(Request $request): JsonResponse
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
        try {

            DB::beginTransaction();
            $message = __('messages.create', ['model' => 'Fiche']);
            $this->technicalFicheRepositoryInterface->create($request);
            DB::commit();
            return response()->json($message);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function showAction($id): JsonResponse
    {
        try{
            return response()->json(
                $this->technicalFicheRepositoryInterface->show($id)
            );
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
    public function addNewItemToItemFicheAction(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = __('messages.update');
            $this->technicalFicheRepositoryInterface->addNewItemToItemFiche($request);
            DB::commit();
            return response()->json($message);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteProductFromItemFicheAction(Request $request, $itemID, $productID)
    {
        try {

            $message = __('messages.delete');
            $this->technicalFicheRepositoryInterface->deleteProductFromItemFiche($request, $itemID, $productID);
            return response()->json($message);

        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function editProductQuantityAction(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = __('messages.update');
            $this->technicalFicheRepositoryInterface->editProductQuantity($request);
            DB::commit();
            return response()->json($message);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }
}
