<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Role;
use App\Http\Services\UserInstance;

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
            'prod_supplierID' => ['required'],
            'prod_unmed' => ['required'],
            'prod_contain' => ['required']
        ],
        [
            'prod_name.required' => 'name is required',
            'prod_supplierID.required' => 'supplier is required',
            'prod_unmed.required' => 'unit. measure is required',
            'prod_contain.rqeuired' => 'unit. contain is required'

        ]);

        try {
            $auth = $request->session()->get('auth-vue');
            $data = $request->all();
            foreach (UserInstance::get_user_roles($auth) as $create):
                if ($create->role_id === Role::CAN_CREATE_PRODUCT || $create->role_id === Role::MANAGER):
                    Product::create($data);
                    return response()
                        ->json("Produto Salvou com sucesso", 200);
                endif;
            endforeach;
            return response()
                ->json("You dont have permission", 400);
        }catch(\Exception){
            return response()
                ->json("Action can't be realised", 400);
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
