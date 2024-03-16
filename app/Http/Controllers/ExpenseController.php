<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Expense\ExpenseRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    protected ExpenseRepositoryInterface $expenseRepositoryInterface;

    public function __construct(
        ExpenseRepositoryInterface $expenseRepositoryInterface
    ){
        $this->expenseRepositoryInterface = $expenseRepositoryInterface;
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
}
