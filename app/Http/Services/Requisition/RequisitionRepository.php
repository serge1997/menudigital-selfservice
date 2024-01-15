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
     * @param $id
     * @param $product_id
     * @throws Exception
     * @see RequisitionInterface
     * @see PurchaseRequisition
     */
    public function checkRequisitionID($id, $product_id, $quantidade)
    {
        //verifique se a requisition existe ou já foi tratado
        $requisition_data = PurchaseRequisition::where('id', $id)->first();
        $itens = RequisitionItem::where([['requisition_id', $id], ['status_id', PurchaseRequisition::REQUISITION_APPROVED]])->get();
        $check_stock = StockEntry::where('requisition_id', $id)->get();

        if ($requisition_data->status_id == PurchaseRequisition::REQUISITION_WAITING){
            throw new Exception("Ação não pode ser concluída. A requisição está no modo pendente.");
        }else if (count($check_stock) >= count($itens) || is_null($requisition_data->id) || $requisition_data->status_id == PurchaseRequisition::REQUISITION_REJECTED){
            throw new Exception("Código de requisição incorreto ou já validado");
        }else{
            $check_product = RequisitionItem::where([ ['requisition_id', $id], ['product_id', $product_id]])->first();
            $info_product_stock = StockEntry::where([['requisition_id', $id], ['productID', $product_id]])->first();
            if (!is_null($info_product_stock)){
                throw new Exception("A entrega desse produto já foi lançado no sistema");
            }
            if (is_null($check_product) || !$check_product->product_id || $check_product->status_id == PurchaseRequisition::REQUISITION_REJECTED){
                throw new Exception("Produto não faz parte da lista de requisition ou foi rejeitado. Consulte a lista e tente novamente");
            }

            if ($check_product->quantity != $quantidade){
                throw new Exception("A quantidade de requisição e de entrega devem ser iguais");
            }
        }

    }
}
