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

    public function getAll(): Collection
    {
        return new Collection(
            Product::where('is_delete', false)
                ->orderBy('prod_name')
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
                throw new Exception("Quantidade entrada está superior ao saldo do produto");
            }
        }
        throw new Exception(Util::PermisionExceptionMessage());

    }
}
