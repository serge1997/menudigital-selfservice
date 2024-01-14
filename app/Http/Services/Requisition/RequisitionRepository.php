<?php
namespace App\Http\Services\Requisition;

use Illuminate\Support\Facades\DB;
use App\Models\PurchaseRequisition;
use App\Models\RequisitionItem;
use App\Models\StockEntry;
use Exception;

class RequisitionRepository implements RequisitionInterface
{
    /**
     * @throws Exception
     * @param $id
     * @see RequisitionInterface
     * @see PurchaseRequisition
     */
    public function checkRequisitionID($id, $product_id)
    {
        //verifique se a requisition existe ou já foi tratado
        $requisition_data = PurchaseRequisition::where('id', $id)->first();
        $check_stock = StockEntry::where('requisition_id', $id)->get();
        if (count($check_stock) > 0 || is_null($requisition_data->id) || $requisition_data->status_id == PurchaseRequisition::REQUISITION_REJECTED)
        {
            throw new Exception("Código de requisição incorreto ou já validado");
        }else if ($requisition_data->status_id == PurchaseRequisition::REQUISITION_WAITING)
        {
            throw new Exception("Ação não pode ser concluída. A requisição está no modo pendente.");
        }else
        {
            $check_product = RequisitionItem::where([ ['requisition_id', $id], ['product_id', $product_id]])->first();
            if (is_null($check_product) || !$check_product->product_id)
            {
                throw new Exception("Produto não faz parte da lista de requisition. Consulte a lista e tente novamente");
            }

        }

    }
}
