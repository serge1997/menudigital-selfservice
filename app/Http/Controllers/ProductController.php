<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Supplier;

class ProductController extends Controller
{
    protected $products;
    protected $suppliers;

    public function __construct()
    {
        $this->products = new Product();
        $this->suppliers = new Supplier();
    }
    public function StoreProduct(Request $request)
    {
        $request->validate([

            'prod_name' => ['required'],
            'prod_supplierID' => ['required']
        ],
        [
            'prod_name.required' => 'name is required',
            'prod_supplierID.required' => 'supplier is required'

        ]);

        try {
            $data = $request->all();
            Product::create($data);
            return response()
                ->json(
                    [
                        "msg_success" => "Produto Salvou com sucesso",
                        "status" => 200
                    ]
                );
        }catch(\Exception){
            return response()
                ->json(
                    [
                        "msg_error" => "Action can't be realised",
                        "stauts" => 404
                    ]
                );
        }
    }

    public function get_product()
    {
        $products = $this->products::where('products.is_delete', false)
            ->select(
                'products.id',
                'products.prod_name',
            )
                ->orderBy('prod_name')
                     ->get();

        $suppliers = $this->suppliers::where('is_delete', false)
            ->select(
                'id',
                'sup_name',
            )
                ->orderBy('sup_name')
                    ->get();
        return response()
            ->json([
                'products' => $products,
                'suppliers' => $suppliers
            ]);
    }

}
