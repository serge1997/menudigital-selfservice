<?php
namespace App\Http\Services\Product;

use App\Models\Product;
use Exception;

class ProductRepositoty
{
    /**
     * @throws Exception
     */
    public static function checkProductName($prod_name)
    {
        $product = Product::where('prod_name', $prod_name)->first();
        if (isset($product->prod_name)):
            throw new Exception("Product always exist");
        endif;
    }
}
