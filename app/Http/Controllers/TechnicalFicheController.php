<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class TechnicalFicheController extends Controller
{
    protected TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface;

    public function __construct(TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface)
    {
        $this->technicalFicheRepositoryInterface = $technicalFicheRepositoryInterface;
    }

    public function addNewItemToItemFicheAction(Request $request)
    {
        try {
            DB::beginTransaction();
            $message = "Ficha técnica editada com sucesso";
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

            $message = "Produto deletado com successo";
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
            $message = "Edição concluida com sucesso";
            $this->technicalFicheRepositoryInterface->editProductQuantity($request);
            DB::commit();
            return response()->json($message);

        }catch(Exception $e){
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
    }
}
