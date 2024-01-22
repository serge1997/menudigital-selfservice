<?php
namespace App\Main\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{

    public function getAll(): Collection
    {
        return new Collection(
            Product::where('is_delete', false)->get()
        );
    }

    public function findById($id)
    {
        return Product::find($id);
    }
}
