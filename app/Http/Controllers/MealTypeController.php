<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Main\MealType\MealTypeRepositoryInterface;
use Exception;

class MealTypeController extends Controller
{
    protected MealTypeRepositoryInterface $mealTypeRepositoryInterface;
    public function __construct(MealTypeRepositoryInterface $mealTypeRepositoryInterface)
    {
        $this->mealTypeRepositoryInterface = $mealTypeRepositoryInterface;
    }

    public function createAction(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'desc_type' => ['required']
            ]);
            $message = "Item salvou com sucesso";
            $this->mealTypeRepositoryInterface->create($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getAllItemType(): JsonResponse
    {
        return response()->json($this->mealTypeRepositoryInterface->getAll());
    }
}
