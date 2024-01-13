<?php

namespace App\Http\Controllers;

use http\Env\Response;
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
                DB::raw('CASE WHEN itens_requisitions.confirm_quantity = 0 THEN itens_requisitions.quantity ELSE itens_requisitions.confirm_quantity END AS quantity'),
                'products.prod_name',
                'departments.name AS department_name'
            )
            ->join('itens_requisitions', 'purchase_requisitions.id', '=', 'itens_requisitions.requisition_id')
                ->join('departments','purchase_requisitions.department_id', '=', 'departments.id')
                    ->join('users', 'purchase_requisitions.user_id', 'users.id')
                        ->join('products','itens_requisitions.product_id', '=', 'products.id' )
                            ->where([['purchase_requisitions.id', $id], ['itens_requisitions.status_id', '<>', PurchaseRequisition::REQUISITION_REJECTED]])
                                ->get();
        return response()->json([
            'requisition_itens' => $requisition,
            'requisition_status'      => RequisitionStatus::all()
            ]);
    }

    public function searchRequisitionCode($requisitionCode)
    {
        $code = DB::table('purchase_requisitions')->select('requisition_code')
            ->where([['requisition_code', 'like', '%'.$requisitionCode.'%'], ['status_id', '<>', PurchaseRequisition::REQUISITION_REJECTED]])
            ->get();

        return response()->json($code);
    }

    public function getRequisitionProduct(Request $request)
    {
        $code = $request->requisition_code;
        $requisition = DB::table('itens_requisitions')
            ->select(
                'itens_requisitions.product_id',
                'suppliers.sup_name',
                'itens_requisitions.quantity',
                'itens_requisitions.confirm_quantity',
                'products.prod_name',
            )
            ->join('products', 'itens_requisitions.product_id', '=', 'products.id')
               // ->join('products','itens_requisitions.product_id', '=', 'products.id' )
                    ->join('suppliers', 'products.prod_supplierID', '=', 'suppliers.id')
                        ->where('itens_requisitions.requisition_code', $code)
                            ->get();

        return response()->json($requisition);

    }

    public function updateRequisitionProductQuantity(Request $request)
    {
        try{
            if ($request->isMethod("post")){

                DB::table('itens_requisitions')
                    ->where([['requisition_id', $request->requisition_id], ['product_id', $request->product_id]])
                    ->update([
                        'confirm_quantity' => $request->quantity
                    ]);

                return response()->json("Quantidade editado com sucesso", 200);
            }
        }catch(Exception $e){
            return response()->json("Ação não pode ser concluída", 422);
        }
    }

    public function confirmPurchaseRequisition(Request $request)
    {
        $request->validate([
            'products_id' => ['required'],
        ],
        [
            'products_id.required' => "Verifique todos os campos sejam preenchido"
        ]);

        try{

            $products = $request->products_id;
            $requisition_id = filter_var($request->requisition_id, FILTER_SANITIZE_NUMBER_INT);
            $status_id = filter_var($request->status_id, FILTER_SANITIZE_NUMBER_INT);
            foreach ($products as $key => $product)
            {
                $check_product = RequisitionItem::where([['product_id', $product], ['requisition_id',$requisition_id]])
                    ->first();
                $check_requisition_id = PurchaseRequisition::where('id', $requisition_id)->first();
                if ($status_id != PurchaseRequisition::REQUISITION_REJECTED && $check_product->status_id != PurchaseRequisition::REQUISITION_REJECTED && $check_requisition_id->status_id != PurchaseRequisition::REQUISITION_REJECTED)
                {
                    // quantity already updated
                    if ($check_product->confirm_quantity)
                    {
                        DB::table('itens_requisitions')
                            ->where([['product_id', $product], ['requisition_id',$requisition_id]])
                            ->update([
                                'status_id' => $status_id ?? PurchaseRequisition::REQUISITION_APPROVED
                            ]);
                    }else {
                        // update with the quantity who are coming from the request

                        DB::table('itens_requisitions')
                            ->where([['product_id', $product], ['requisition_id',$requisition_id]])
                            ->update([
                                'status_id' => $status_id ?? PurchaseRequisition::REQUISITION_APPROVED,
                                    'quantity' => $request->quantity[$key]
                            ]);
                    }
                }
                DB::table('itens_requisitions')
                    ->where([['product_id', $product], ['requisition_id',$requisition_id]])
                    ->update([
                        'status_id' => $status_id
                    ]);
                DB::table('purchase_requisitions')
                    ->where('id', $requisition_id)
                    ->update([
                        'status_id' => $status_id,
                        'response_date' => Util::Today()
                    ]);
            }
            $message = $status_id == PurchaseRequisition::REQUISITION_REJECTED ? "Requsição foi rejectada com sucesso" : "Requisição confirmado com sucesso";
            return response()->json($message, 200);
        }catch(Exception $e) {
            return response()->json("Ação não pode ser concluída ".$e->getMessage(). " ".$e->getLine(), 500);
        }
    }

    public function setRequisitionItemStatusToRejected($requisition_id, $product_id)
    {
        try {
            $requisition_id = filter_var($requisition_id, FILTER_SANITIZE_NUMBER_INT);
            $product_id = filter_var($product_id, FILTER_SANITIZE_NUMBER_INT);
            RequisitionItem::where([['requisition_id', $requisition_id], ['product_id', $product_id]])
                ->update([
                    'status_id' => PurchaseRequisition::REQUISITION_REJECTED
                ]);

            return response()->json("Produto foi rejeitado", 200);
        }catch(Exception $e) {
            return response()->json("Ação não pode ser concluída", 500);
        }


    }
}
