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
use App\Http\Requests\StoreProductRequest;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;

class ProductController extends Controller
{
    protected $products;
    protected $suppliers;
    protected ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(
        ProductRepositoryInterface $productRepositoryInterface,
        private readonly TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface
    )
    {
        $this->products = new Product();
        $this->suppliers = new Supplier();
        $this->productRepositoryInterface = $productRepositoryInterface;
    }
    public function StoreProduct(StoreProductRequest $request)
    {
        try {
            $message = __('messages.create', ['model' => 'Product']);
            $this->productRepositoryInterface->create($request);
            return response()
                ->json($message);
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

    public function update(StoreProductRequest $request)
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

    public function deleteAction(Request $request)
    {
        try {
            $message = __('messages.delete');
            $this->productRepositoryInterface->delete($request, $this->technicalFicheRepositoryInterface);
            return response()
                ->json($message);
        }catch(Exception $e){
            return response()->json("{$e->getMessage()}", 500);
        }
    }

    public function searchProductAction(Request $request)
    {
        try{
            return response()
                ->json($this->productRepositoryInterface->searchProduct($request));
        }catch(Exception $e){
            return response()
                ->json($e->getMessage(), 500);
        }
    }
}
