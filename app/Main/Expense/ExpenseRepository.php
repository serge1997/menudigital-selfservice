<?php
namespace App\Main\Expense;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function listAll()
    {
        return ExpenseResource::collection(
            Expense::all()
        );
    }

    public function listFilterData($request)
    {
        $whereClause = "pr.is_delete = 0 ";
        if ($request->year){
            $whereClause .= "AND SUBSTRING(expenses.created_at, 1, 4) LIKE '%{$request->year}%' ";
        }
        if ($request->month){
            $whereClause .= "AND MONTHNAME(expenses.created_at) LIKE '%{$request->month}%' ";
        }
        if ($request->item){
            $whereClause .= "AND pr.prod_name LIKE '%{$request->item}%' ";
        }


        $query = Expense::select('*')
            ->join('products as pr', 'expenses.product_id', '=', 'pr.id')
                ->whereRaw($whereClause)
                    ->get();
        return ExpenseResource::collection($query);
    }
}
