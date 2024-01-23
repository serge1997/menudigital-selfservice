<?php
namespace App\Main\TechnicalFiche;

use App\Models\Technicalfiche;
use Illuminate\Database\Eloquent\Collection;
use App\Main\Stock\StockRepositoryInterface;
use App\Main\Product\ProductRepositoryInterface;
use Exception;
use App\Models\Role;
use App\Http\Services\UserInstance;
use App\Traits\AuthSession;

class TechnicalFicheRepository implements TechnicalFicheRepositoryInterface
{
    use AuthSession { checkOnlyManager as private; autth as protected; }

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

    public function create($request)
    {
        $itemID = $request->itemID;
        $productIds = $request->productID;
        $quantity = $request->quantity;

        $this->checkOnlyManager($request);
        $this->beforeSaveItem($itemID);
        foreach ($productIds as $key => $productID):

            $stock = $this->stockRepositoryInterface->findLastProductEntry($productID);
            $product = $this->productRepositoryInterface->findById($productID);

                if ($product->prod_unmed != "bt"):
                    $qty = $quantity[$key];
                    $fiche = new Technicalfiche();
                    $fiche->itemID = $itemID;
                    $fiche->productID = $productID;
                    $fiche->quantity = $qty;
                    $fiche->cost = ($qty * $stock->unitCost) / $product->prod_contain;
                    $fiche->save();
                else:
                    $qty = $quantity[$key];
                    $fiche = new Technicalfiche();
                    $fiche->itemID = $itemID;
                    $fiche->productID = $productID;
                    $fiche->quantity = $qty;
                    $fiche->cost = $stock->unitCost;
                    $fiche->save();
                endif;
        endforeach;
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

    public function beforeSaveItem($item_id)
    {
        if (Technicalfiche::where('itemID', $item_id)->exists()){
            throw new Exception("Ficha técnica já existe");
        }else{

        }
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
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $edit):
            if ($edit->role_id == Role::MANAGER):
                foreach ($productIds  as $key => $productId){
                    $this->beforeSave($itemId, $productId);
                    $productInfo = $this->productRepositoryInterface->findById($productId);
                    $productCost = $this->stockRepositoryInterface->findLastProductEntry($productId);
                    $fiche = new Technicalfiche();
                    $fiche->itemID = $itemId;
                    $fiche->productID = $productId;
                    $fiche->quantity = $quantitys[$key];
                    $fiche->cost = $productInfo->prod_unmed == "bt" ? $productCost->unitCost : ($quantitys[$key] * $productCost->unitCost) / $productInfo->prod_contain;
                    $fiche->save();

                }
            endif;
           return;
        endforeach;
        throw new Exception("Você não tem permissão");
    }

    public function deleteProductFromItemFiche($request, $item_id, $product_id): void
    {
        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $confirm):
            if ($confirm->role_id == Role::MANAGER):
                Technicalfiche::where([['itemID', $item_id], ['productID', $product_id]])
                    ->delete();
                return;
            endif;
        endforeach;
        throw new Exception("Você não tem permissão");
    }

    public function editProductQuantity($request): void
    {
        $item_id = $request->itemId;
        $quantitys = $request->quantity;
        $products = $request->products;

        $auth = $request->session()->get('auth-vue');
        foreach (UserInstance::get_user_roles($auth) as $edit):
            if ($edit->role_id == Role::MANAGER):
                foreach ($products as $key => $product){
                    $productInfo = $this->productRepositoryInterface->findById($product);
                    $productCost = $this->stockRepositoryInterface->findLastProductEntry($product);
                    Technicalfiche::where([['itemID', $item_id], ['productID', $product]])
                        ->update([
                            'quantity' => $quantitys[$key],
                            'cost' => $productInfo->prod_unmed == "bt" ? $productCost->unitCost : ($quantitys[$key] * $productCost->unitCost) / $productInfo->prod_contain
                        ]);
                }
                return;
            endif;
        endforeach;
        throw new Exception("Você não tem permissão");
    }
}
