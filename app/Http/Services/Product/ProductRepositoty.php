<?php
namespace App\Http\Services\Product;

use App\Models\Product;
use App\Models\StockEntry;
use Exception;
use Illuminate\Support\Facades\DB;

class ProductRepositoty
{
    /**
     * @throws Exception
     */
    public static function checkProductNameCreate($prod_name): void
    {
        $product = Product::where('prod_name', $prod_name)->first();
        if (isset($product->prod_name)):
            throw new Exception("Product always exist");
        endif;
    }

    /**
     * @throws Exception
     */
    public static function checkProductNameUpdate($prod_name, $prod_id): void
    {
        $products = Product::where('id', '<>', $prod_id)->get();
        foreach ($products as $product)
        {
            if ($product->prod_name === $prod_name){
                throw new Exception("Para mudar o nome do produto escolhe um nome ainda não cadastrado. Esse nome já existe.");
            }
        }
    }

    public function getLastProductCost($id)
    {
        $lastCost = DB::table('stock_entries')->select(
                DB::raw('MAX(emissao) AS emissao'),
                'unitCost',
                'productID',
            )->where('productID', $id)
                ->groupBy('unitCost', 'productID')
                    ->first();

        return  $lastCost;
    }
}
