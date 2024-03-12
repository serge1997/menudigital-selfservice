<?php
namespace App\Main\Product;

use App\Http\Services\Util\Util;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Saldo;
use App\Traits\Permission;
use Exception;

class ProductRepository implements ProductRepositoryInterface
{
    use Permission;

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
            $saldo->update([
                'saldoFinal' => $saldo->saldoFinal - $request->quantity
            ]);
            return;
        }
        throw new Exception(Util::PermisionExceptionMessage());

    }
}
