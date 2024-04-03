<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Expense\ExpenseRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreExpense;

class ExpenseController extends Controller
{
    protected ExpenseRepositoryInterface $expenseRepositoryInterface;

    public function __construct(
        ExpenseRepositoryInterface $expenseRepositoryInterface
    ){
        $this->expenseRepositoryInterface = $expenseRepositoryInterface;
    }

    public function createExpenseProductAction(StoreExpense $request)
    {
        try{
            $message = __('messages.create', ['model' => 'Expense']);
            $this->expenseRepositoryInterface->createExpenseProduct($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function createExpenseMenuItemAction(StoreExpense $request)
    {
        try{
            $message = __('messages.create', ['model' => 'Expense']);
            $this->expenseRepositoryInterface->createExpenseItemMenu($request);
            return response()->json($message);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function listAllAction(): JsonResponse
    {
        try{
            return response()
                ->json($this->expenseRepositoryInterface->listAll());
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function listFilterAction(Request $request): JsonResponse
    {
        try{
            $filterData = $this->expenseRepositoryInterface->listFilterData($request);
            return response()
                ->json([
                    'bar' => $filterData[0],
                    'item' => $filterData[1]
                ]);
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }
}
