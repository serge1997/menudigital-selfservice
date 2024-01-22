<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Role;
use App\Http\Services\UserInstance;
use App\Http\Services\Product\ProductRepositoty;
use App\Main\Product\ProductRepositoryInterface;
use Exception;

class ProductController extends Controller
{
    protected $products;
    protected $suppliers;
    protected ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->products = new Product();
        $this->suppliers = new Supplier();
        $this->productRepositoryInterface = $productRepositoryInterface;
    }
    public function StoreProduct(Request $request)
    {
        $request->validate([

            'prod_name' => ['required'],
            'prod_supplierID' => ['required'],
            'prod_unmed' => ['required'],
            'prod_contain' => ['required'],
            'min_quantity' => ['required']
        ],
        [
            'prod_name.required' => 'name is required',
            'prod_supplierID.required' => 'supplier is required',
            'prod_unmed.required' => 'unit. measure is required',
            'prod_contain.rqeuired' => 'unit. contain is required',
            'min_quantity.required' => 'minimum quantity required'

        ]);

        try {
            ProductRepositoty::checkProductNameCreate($request->prod_name);
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
                ->json("You dont have permission", 500);
        }catch(\Exception $e){
            return response()
                ->json($e->getMessage(), 500);
        }
    }

    public function listAllProducts(): JsonResponse
    {
        try {
            $apiProductResponse = $this->productRepositoryInterface->getAll();
            return response()->json($apiProductResponse);
        }catch(Exception $e) {
            return response()->json($e->getMessage());
        }
        return response()
            ->json(Product::where('is_delete', false)->get());
    }
    public function showProductToEdit($id)
    {
        return response()->json(Product::where('id', $id)->first());
    }

    public function update(Request $request)
    {
        try{
            if ($request->isMethod('put')){
                ProductRepositoty::checkProductNameUpdate($request->prod_name, $request->id);
                $auth = $request->session()->get('auth-vue');
                foreach (UserInstance::get_user_roles($auth) as $create):
                    if ($create->role_id === Role::CAN_CREATE_PRODUCT || $create->role_id === Role::MANAGER):
                        DB::table('products')
                            ->where('id', $request->id)
                            ->update([
                                'prod_name' => $request->prod_name,
                                'min_quantity' => $request->min_quantity,
                                'prod_desc' => $request->prod_desc,
                                'prod_contain' => $request->prod_contain,
                                'prod_supplierID' => $request->prod_supplierID
                            ]);
                        return response()->json("Product updated successfully");
                    endif;
                endforeach;
                return response()->json("You dont have permission", 500);
            }
        }catch (Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function delete($id, Request $request)
    {
        try {
            if ($request->isMethod('delete')){
                $auth = $request->session()->get('auth-vue');
                foreach (UserInstance::get_user_roles($auth) as $delete):
                    if ($delete->role_id === Role::MANAGER || $delete->role_id === Role::CAN_CREATE_PRODUCT):
                        Product::where('id', $id)
                            ->update([
                                'is_delete' => true
                            ]);
                        return response()->json("Product deleted successfully");
                    endif;
                endforeach;
                return response()->json("You dont have permission", 500);
            }
        }catch(Exception $e){
            return response()->json("Product cant be deleted");
        }
    }

}
