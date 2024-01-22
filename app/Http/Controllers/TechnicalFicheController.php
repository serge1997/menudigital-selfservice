<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;
use Exception;

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

            $message = "Ficha técnica editada com sucesso";
            $this->technicalFicheRepositoryInterface->addNewItemToItemFiche($request);
            return response()->json($message);

        }catch(Exception $e){
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
}
