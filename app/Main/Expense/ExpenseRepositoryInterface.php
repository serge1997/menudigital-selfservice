<?php
namespace App\Main\Expense;

interface ExpenseRepositoryInterface
{
    public function listAll();
    public function listFilterData($request);
}
