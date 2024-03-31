<?php
namespace App\Main\PurchaseRequisition;
use App\Models\RequisitionItem;
use App\Models\PurchaseRequisition;
use App\Http\Services\Util\Util;
use App\Traits\Permission;
use App\Events\RequisitionSended;
use App\Http\Services\Product\ProductRepositoty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseRequisitionSended;
use App\Models\User;

class PurchaseRequisitionRepository implements PurchaseRequisitionRepositoryInterface
{
    use Permission;

    private ProductRepositoty $serviceProduct;

    public function __construct( ProductRepositoty $serviceProduct)
    {
        $this->serviceProduct = $serviceProduct;
    }

    public function create($request)
    {
            $date = new \DateTime(substr($request->delivery_date, 0, 10));
            $delivery_date = $date->format('Y-m-d');
            $requisition_code = "";
            $string_format = Util::randomString();
            $number_format = Util::randomNumber();
            $requisition_code .= $string_format;
            $requisition_code.=$number_format;
            $requisition_code.= str_replace('-', '',$date->format('m-d'));
            $product_ids = $request->products_id;
            $req_values = $request->all();
            $requision = new PurchaseRequisition($req_values);
            $requision->status_id = PurchaseRequisition::REQUISITION_WAITING;
            $requision->delivery_date = $delivery_date;
            $requision->requisition_code = $requisition_code;
            $requision->save();
            foreach ($product_ids as $key => $id){
                $cost = $this->serviceProduct->getLastProductCost($id);
                $cost = $cost->unitCost ?? 0;
                $item = new RequisitionItem();
                $item->requisition_id = $requision->id;
                $item->product_id = $id;
                $item->cost = $cost;
                $item->requisition_code = $requisition_code;
                $item->status_id = PurchaseRequisition::REQUISITION_WAITING;
                $item->quantity = $request->quantity[$key];
                $item->confirm_quantity = 0;
                $item->total = $cost * $request->quantity[$key];
                $item->save();
            }
            Mail::to(User::findOrFail(User::GERENTE)->first())
                ->queue((new PurchaseRequisitionSended($requision))->onConnection('sync'));
    }

    public function listAll()
    {
        $requisition = DB::table('purchase_requisitions')
            ->select(
                'purchase_requisitions.id',
                'purchase_requisitions.delivery_date',
                'purchase_requisitions.requisition_code',
                DB::raw("DATE_FORMAT(purchase_requisitions.delivery_date, '%d/%m/%Y') as delivery_date"),
                DB::raw("DATE_FORMAT(purchase_requisitions.created_at, '%d/%m/%Y') as created_at"),
                'users.name AS require_name',
                'requisitions_status.stat_desc',
                'departments.name'
            )
            ->join('departments', 'purchase_requisitions.department_id', '=', 'departments.id')
                ->join('requisitions_status','purchase_requisitions.status_id', '=', 'requisitions_status.id')
                    ->join('users', 'purchase_requisitions.user_id', 'users.id')
                        ->orderBy('purchase_requisitions.id', 'DESC')
                            ->get();
        return $requisition;
    }

    public function findById($id)
    {
        $requisition = DB::table('purchase_requisitions')
            ->select(
                'itens_requisitions.product_id',
                'purchase_requisitions.observation',
                'itens_requisitions.status_id as show_status',
                'itens_requisitions.confirm_quantity',
                'itens_requisitions.quantity',
                'products.prod_name',
            )
                ->join('itens_requisitions', 'itens_requisitions.requisition_id', 'purchase_requisitions.id')
                    ->join('products','itens_requisitions.product_id', '=', 'products.id' )
                        ->where('purchase_requisitions.id', $id)
                            ->get();
        return $requisition;
    }

    public function deleteByRequisitionIdProductId($requisition_id, $product_id)
    {
        RequisitionItem::where([
            ['requisition_id', $requisition_id],
            ['product_id', $product_id]
        ])->update([
            'is_delete' => true
        ]);
    }
    public function deleteByRequisitionId($requisition_id)
    {
        PurchaseRequisition::where('id', $requisition_id)
            ->update([
                'is_delete' => true
            ]);
    }
}
