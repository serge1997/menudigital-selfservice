<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Services\Supplier\SupplierRepository;
use App\Models\Role;
use App\Models\Supplier;
use Exception;


class SupplierController extends Controller
{
    protected SupplierRepository $supplierRespository;

    public function __construct(SupplierRepository $supplierRespository)
    {
        $this->supplierRespository = $supplierRespository;
    }
    public function StoreSupplier(Request $request): JsonResponse
    {
        $request->validate([
            "sup_name" => ["required"],
            "sup_tel" => ["required"],
        ],
            [
                "sup_name.required" => "name is required",
                "sup_tel.required" => "contact is required"
            ]);

        try {

            $this->supplierRespository->beforeSave($request->sup_name);
            $data = $request->all();
            Supplier::create($data);
            return response()
                ->json("supplier created successfully",200);

        }catch(\Exception $e){
            return response()
                ->json($e->getMessage(), 400);
        }
    }

    public function getSuppliers()
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
                return response()->json("Supplier edited successfully");
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
                return response()->json("Supplier deleted successfully.");
            }
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
