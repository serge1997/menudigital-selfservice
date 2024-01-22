<?php
namespace App\Main\TechnicalFiche;

use App\Models\Technicalfiche;
use Illuminate\Database\Eloquent\Collection;
use App\Main\Stock\StockRepositoryInterface;
use App\Main\Product\ProductRepositoryInterface;
use Exception;
use App\Models\Role;
use App\Http\Services\UserInstance;

class TechnicalFicheRepository implements TechnicalFicheRepositoryInterface
{
    protected StockRepositoryInterface $stockRepositoryInterface;
    protected ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(
        StockRepositoryInterface $stockRepositoryInterface,
        ProductRepositoryInterface $productRepositoryInterface
        )
    {
        $this->stockRepositoryInterface = $stockRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
    }


    public function findByItemId($id): Collection
    {
        return new Collection(
            Technicalfiche::select('*')
            ->join('products', 'technicalfiches.productID', '=', 'products.id')
                ->where('itemID', $id)
                    ->get()
        );
    }

    public function beforeSave($item_id, $product_id)
    {
        $fiche = Technicalfiche::where('itemID', $item_id)->get();
        foreach ($fiche as $value){
            if ($value->productID == $product_id){
                throw new Exception("Produto já existe na ficha tecnica desse item");
            }
        }
    }

    public function addNewItemToItemFiche($request): void
    {
        $itemId = $request->itemID;
        $productIds = $request->productID;
        $quantitys = $request->quantity;

        foreach ($productIds  as $key => $productId){
            $this->beforeSave($itemId, $productId);
            $productInfo = $this->productRepositoryInterface->findById($productId);
            $productCost = $this->stockRepositoryInterface->findLastProductEntry($productId);
            //var_dump($productCost->unitCost); die;
            $fiche = new Technicalfiche();
            $fiche->itemID = $itemId;
            $fiche->productID = $productId;
            $fiche->quantity = $quantitys[$key];
            $fiche->cost = $productInfo->prod_unmed == "bt" ? $productCost->unitCost : ($quantitys[$key] * $productCost->unitCost) / $productInfo->prod_contain;
            $fiche->save();

        }
    }

    public function deleteProductFromItemFiche($request, $item_id, $product_id): void
    {
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $confirm):
            if ($confirm->role_id == Role::MANAGER):
                Technicalfiche::where([['itemID', $item_id], ['productID', $product_id]])
                    ->delete();
            endif;
        endforeach;
        throw new Exception("Você não tem permissão");
    }
}
