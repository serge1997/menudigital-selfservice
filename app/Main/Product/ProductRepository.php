<?php
namespace App\Main\Product;

use App\Http\Services\Util\Util;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Saldo;
use App\Traits\AuthSession;
use App\Traits\Permission;
use Exception;

class ProductRepository implements ProductRepositoryInterface
{
    use Permission, AuthSession { AuthSession::autth insteadof Permission; }


    public function create($request)
    {
        if ( $this->can_manage($request) || $this->can_create_product($request)){
            $this->beforeSave($request->prod_name);
            Product::create($request->validated());
            return true;
        }
        throw new Exception(__('messages.permission'));
    }

    public function beforeSave($product)
    {
        if ($product = Product::where('prod_name', $product)->exists()){
            throw new Exception("Product always exist");
        }
    }

    public function getAll(): Collection
    {
        return new Collection(
            Product::orderBy('prod_name')
                ->get()
        );
    }

    public function findById($id): Product
    {
        return Product::find($id);
    }

    public function expenseProduct($request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            $product = $this->findById($request->product_id);
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
                $expense->save();
                return;
            }else{
                throw new Exception(__('messages.create_expense'));
            }
        }
        throw new Exception(__('messages.permission'));

    }
    public function searchProduct($request)
    {
        return Product::where('prod_name', 'like', "%{$request->search}%")
            ->get();
    }

    public function delete($request)
    {
        if ($this->can_manage($request) || $this->can_create_product($request)){
            return Product::where('id', $request->product_id)
                ->update([
                    'is_delete' => true
                ]);
        }
        throw new Exception(__('messages.permission'));

    }
}
