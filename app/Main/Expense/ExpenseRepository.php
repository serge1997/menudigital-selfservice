<?php
namespace App\Main\Expense;

use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use App\Main\Stock\StockRepositoryInterface;
use App\Main\Product\ProductRepositoryInterface;
use App\Main\MenuItem\MenuItemRepositoryInterface;
use Exception;
use App\Http\Services\Util\Util;
use App\Models\Saldo;
use App\Traits\Permission;
use App\Traits\AuthSession;
use App\Models\Technicalfiche;
use Carbon\Carbon;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    use Permission, AuthSession { AuthSession::autth insteadof Permission; }

    protected StockRepositoryInterface $stockRepositoryInterface;
    protected ProductRepositoryInterface $productRepositoryInterface;
    protected MenuItemRepositoryInterface $menuItemRepositoryInterface;

    public function __construct(
        StockRepositoryInterface $stockRepositoryInterface,
        ProductRepositoryInterface $productRepositoryInterface,
        MenuItemRepositoryInterface $menuItemRepositoryInterface
    ){
        $this->stockRepositoryInterface = $stockRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->menuItemRepositoryInterface = $menuItemRepositoryInterface;
    }

    public function createExpenseProduct($request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            $product = $this->productRepositoryInterface->findById($request->product_id);
            $cost = $this->stockRepositoryInterface->findLastProductEntry($request->product_id);
            $totalCost = $product->prod_unmed !== "bt" ? ($request->quantity * $cost->unitCost) / $product->prod_contain : $request->quantity * $cost->unitCost;
            $saldo = Saldo::where('productID', $product->id)->first();
            if ($saldo->saldoFinal > $request->quantity){
                $saldo::where('productID', $product->id)->update([
                    'saldoFinal' => $saldo->saldoFinal - $request->quantity
                ]);
                $expense = new Expense();
                $expense->product_id = $product->id;
                $expense->user_id = $this->autth($request);
                $expense->quantity = $request->quantity;
                $expense->item_id = $request->item_id ?? null;
                $expense->observation = $request->observation;
                $expense->unit_cost = $cost->unitCost;
                $expense->total_cost = $totalCost;
                $expense->month_year = Carbon::now()->isoFormat('MM/Y');
                $expense->save();
                return;
            }else{
                throw new Exception(__('messages.create_expense'));
            }
        }
        throw new Exception(__('messages.permission'));
    }

    public function createExpenseItemMenu($request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            $item = $this->menuItemRepositoryInterface->find($request->item_id);
            $fiches = Technicalfiche::where('itemID', $item->id)->get();
            foreach ($fiches as $fiche) {
                $saldo = Saldo::where('productID', $fiche->productID)->first();
                $cost = $this->stockRepositoryInterface->findLastProductEntry($fiche->productID);
                $productData = $this->productRepositoryInterface->findById($fiche->productID);
                $ficheQuantity = Technicalfiche::where([
                    ['productID', $saldo->productID],
                    ['itemID', $request->item_id]
                ])->first();
                if ($saldo->saldoFinal > ($request->quantity * $ficheQuantity->quantity)){
                    $saldo::where('productID', $saldo->productID)->update([
                        'saldoFinal' => $saldo->saldoFinal - ($request->quantity * $ficheQuantity->quantity)
                    ]);
                    $quantityCalc = $request->quantity * $ficheQuantity->quantity;
                    $totalCost = $productData->prod_unmed !== "bt" ? ($quantityCalc * $cost->unitCost) / $productData->prod_contain : $request->quantity * $cost->unitCost;
                    $expense = new Expense();
                    $expense->product_id = $saldo->productID;
                    $expense->user_id = $this->autth($request);
                    $expense->quantity =  $request->quantity * $ficheQuantity->quantity;
                    $expense->item_id = $request->item_id;
                    $expense->observation = $request->observation;
                    $expense->total_cost = $totalCost;
                    $expense->unit_cost = $cost->unitCost;
                    $expense->month_year = Carbon::now()->isoFormat('MM/Y');
                    $expense->save();
                }else{
                    throw new Exception(__('messages.create_expense'));
                }
            }
        }else {
            throw new Exception(__('messages.permission'));
        }
    }

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
        $barChart = Expense::select(
            DB::raw('SUM(total_cost) as total'),
            'month_year'
        )
            ->join('products as pr', 'expenses.product_id', 'pr.id')
                ->whereRaw($whereClause)
                    ->groupBy('month_year')
                        ->get();

        $query = Expense::select('*')
            ->join('products as pr', 'expenses.product_id', '=', 'pr.id')
                ->whereRaw($whereClause)
                    ->get();
        return [$barChart, ExpenseResource::collection($query)];
    }
}
