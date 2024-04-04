<?php
namespace App\Main\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;
    public function findById($id): Product;
    public function expenseProduct($request); //despesa de produto
}
