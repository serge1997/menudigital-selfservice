<?php
namespace App\Main\Expense;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function listAll()
    {
        return ExpenseResource::collection(
            Expense::all()
        );
    }
}
