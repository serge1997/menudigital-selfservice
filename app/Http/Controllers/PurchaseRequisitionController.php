<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\RequisitionItem;
use App\Models\PurchaseRequisition;
use App\Http\Requests\PurchaseRequisitionRequest;
use App\Http\Services\Product\ProductRepositoty;
use Exception;
use App\Http\Services\Util\Util;
use Illuminate\Support\Facades\DB;
use App\Models\RequisitionStatus;

class PurchaseRequisitionController extends Controller
{
    public function create(PurchaseRequisitionRequest $request, ProductRepositoty $product)
    {
        try{
            $request->validated();
            $date = new \DateTime($request->delivery_date);
            $delivery_date = $date->format('Y-m-d');
            $requisition_code = "";
            $string_format = Util::randomString();
            $number_format = Util::randomNumber();
            $requisition_code .= $string_format;
            $requisition_code.=$number_format;
            $requisition_code.=str_replace('-', '',$date->format('m-d'));
            $product_ids = $request->products_id;
            $req_values = $request->all();
            $requision = new PurchaseRequisition($req_values);
            $requision->status_id = PurchaseRequisition::REQUISITION_WAITING;
            $requision->delivery_date = $delivery_date;
            $requision->requisition_code = $requisition_code;
            $requision->save();
            foreach ($product_ids as $key => $id){
                $cost = $product->getLastProductCost($id);
                $cost = $cost->unitCost ?? 0;
                $item = new RequisitionItem();
                $item->requisition_id = $requision->id;
                $item->product_id = $id;
                $item->cost = $cost;
                $item->requisition_code = $requisition_code;
                $item->status_id = PurchaseRequisition::REQUISITION_WAITING;
                $item->quantity = $request->quantity[$key];
                $item->total = $cost * $request->quantity[$key];
                $item->save();
            }
            return response()->json("Requisição salvou com sucesso");
        }catch (Exception $e){
            return response($e->getMessage(), 422);
        }

            //$request->validated();
            //$cost->unitCost ?? $cost = 0
            //return response()->json("Caiu aqui".$cost);

    }

    public function index(): JsonResponse
    {
        $requisition = DB::table('purchase_requisitions')
            ->select(
                'purchase_requisitions.id',
                'purchase_requisitions.delivery_date',
                'purchase_requisitions.requisition_code',
                'purchase_requisitions.delivery_date',
                'users.name AS require_name',
                'requisitions_status.stat_desc',
                'departments.name'
            )
            ->join('departments', 'purchase_requisitions.department_id', '=', 'departments.id')
                ->join('requisitions_status','purchase_requisitions.status_id', '=', 'requisitions_status.id')
                    ->join('users', 'purchase_requisitions.user_id', 'users.id')
                        ->orderBy('purchase_requisitions.id', 'DESC')
                            ->get();
        return response()->json($requisition);
    }

    public function getRequisitionToUpdate($id): JsonResponse
    {
        $requisition = DB::table('purchase_requisitions')
            ->select(
                'purchase_requisitions.id',
                'purchase_requisitions.delivery_date',
                'users.name AS require_name',
                'itens_requisitions.product_id',
                'itens_requisitions.quantity',
                'products.prod_name',
                'departments.name AS department_name'
            )
            ->join('itens_requisitions', 'purchase_requisitions.id', '=', 'itens_requisitions.requisition_id')
                ->join('departments','purchase_requisitions.department_id', '=', 'departments.id')
                    ->join('users', 'purchase_requisitions.user_id', 'users.id')
                        ->join('products','itens_requisitions.product_id', '=', 'products.id' )
                            ->where('purchase_requisitions.id', $id)
                                ->get();
        return response()->json([
            'requisition_itens' => $requisition,
            'requisition_status'      => RequisitionStatus::all()
            ]);
    }
}
