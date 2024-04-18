<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\Supplier\SupplierRepository;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Product;
use Exception;
use App\Http\Requests\StoreSupplierRequest;


class SupplierController extends Controller
{
    protected SupplierRepository $supplierRespository;

    public function __construct(SupplierRepository $supplierRespository)
    {
        $this->supplierRespository = $supplierRespository;
    }
    public function StoreSupplier(StoreSupplierRequest $request): JsonResponse
    {
        try {
            $request->validated();
            $this->supplierRespository->beforeSave($request->sup_name);
            $data = $request->all();
            Supplier::create($data);
            return response()
                ->json(__('messages.create', ['model' => 'Supplier']),200);

        }catch(\Exception $e){
            return response()
                ->json($e->getMessage(), 400);
        }
    }

    public function getSuppliers(): JsonResponse
    {
        return response()
            ->json(Supplier::select('id', 'sup_name', 'sup_email', 'sup_tel')->where('is_delete', false)->get());
    }
    public function getToUpdate($id): JsonResponse
    {
        return response()->json(Supplier::where('id', $id)->first());
    }

    public function update(Request $request)
    {
        try {
            if ($request->isMethod('put')):
                Supplier::where('id', $request->id)
                    ->update([
                        'sup_name'         => $request->sup_name,
                        'sup_personID'     => $request->sup_personID,
                        'sup_tel'          => $request->sup_tel,
                        'sup_city'         =>  $request->sup_city,
                        'sup_neighborhood' => $request->sup_neighborhood,
                        'sup_email'        => $request->sup_email
                    ]);
                return response()->json(__('messages.update'));
            endif;
        }catch (Exception $e){
            return response()->json($e->getMessage(), 400);
        }
    }

    public function delete($id, Request $req)
    {
        try {
            if ($req->isMethod('delete')){

                Supplier::where('id', $id)
                    ->update([
                        'is_delete' => true
                    ]);
                return response()->json(__('messages.delete'));
            }
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     * @see Product
     */
    public function getProductSupplier($id): JsonResponse
    {
       return response()->json(Product::find($id)->supplier);
    }
}
