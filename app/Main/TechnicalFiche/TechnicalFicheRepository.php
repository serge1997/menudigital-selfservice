<?php
namespace App\Main\TechnicalFiche;

use App\Models\Technicalfiche;
use Illuminate\Database\Eloquent\Collection;
use App\Main\Stock\StockRepositoryInterface;
use App\Main\Product\ProductRepositoryInterface;
use App\Main\Restaurant\RestaurantRepositoryInterface;
use Exception;
use App\Models\Role;
use App\Http\Services\UserInstance;
use App\Http\Services\Util\Util;
use App\Traits\AuthSession;
use App\Traits\Permission;
use Illuminate\Support\Facades\DB;

class TechnicalFicheRepository implements TechnicalFicheRepositoryInterface
{
    use AuthSession, Permission;

    protected StockRepositoryInterface $stockRepositoryInterface;
    protected ProductRepositoryInterface $productRepositoryInterface;
    protected RestaurantRepositoryInterface $restaurantRepositoryInterface;

    public function __construct(
        StockRepositoryInterface $stockRepositoryInterface,
        ProductRepositoryInterface $productRepositoryInterface,
        RestaurantRepositoryInterface $restaurantRepositoryInterface
        )
    {
        $this->stockRepositoryInterface = $stockRepositoryInterface;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->restaurantRepositoryInterface = $restaurantRepositoryInterface;
    }

    public function create($request)
    {
        $itemID = $request->itemID;
        $productIds = $request->productID;
        $quantity = $request->quantity;

        // $this->checkOnlyManager($request);
        if ($this->can_manage($request) || $this->can_create_product($request)):
            $this->beforeSaveItem($itemID);
            foreach ($productIds as $key => $productID):

                $stock = $this->stockRepositoryInterface->findLastProductEntry($productID);
                $product = $this->productRepositoryInterface->findById($productID);
                $rest_cost_info = $this->restaurantRepositoryInterface->find();
            //var_dump($rest_cost_info['loss_margin']); die;

                    if ($product->prod_unmed != "bt"):
                        $qty = $quantity[$key];
                        $cost = ($qty * $stock->unitCost) / $product->prod_contain;
                        $fiche = new Technicalfiche();
                        $fiche->itemID = $itemID;
                        $fiche->productID = $productID;
                        $fiche->quantity = $qty;
                        $fiche->cost = $cost;
                        $fiche->loss_margin = $cost * $rest_cost_info['loss_margin'] / 100;
                        $fiche->fix_margin = $cost * $rest_cost_info['fix_margin'] / 100;
                        $fiche->variable_margin = $cost * $rest_cost_info['variable_margin'] / 100;
                        $fiche->save();
                    else:
                        $qty = $quantity[$key];
                        $fiche = new Technicalfiche();
                        $fiche->itemID = $itemID;
                        $fiche->productID = $productID;
                        $fiche->quantity = $qty;
                        $fiche->cost = $stock->unitCost;
                        $fiche->loss_margin = $stock->unitCost * $rest_cost_info['loss_margin'] / 100;
                        $fiche->fix_margin = $stock->unitCost * $rest_cost_info['fix_margin'] / 100;
                        $fiche->variable_margin = $stock->unitCost * $rest_cost_info['variable_margin'] / 100;
                        $fiche->save();
                    endif;
            endforeach;
        else:
            throw new Exception(Util::PermisionExceptionMessage());
        endif;
    }

    public function show(string $id): Collection
    {
        $fiche = Technicalfiche::select(
                'technicalfiches.itemID',
                'technicalfiches.productID',
                'menuitems.item_name',
                'products.prod_name',
                'technicalfiches.quantity',
                'technicalfiches.cost',
                'technicalfiches.fix_margin',
                'technicalfiches.loss_margin',
                'technicalfiches.variable_margin',
                DB::raw(
                    'SUM(technicalfiches.cost) + SUM(technicalfiches.fix_margin) + SUM(technicalfiches.variable_margin) + SUM(technicalfiches.loss_margin) as total'
                ),
                'products.prod_unmed'
            )
                ->where('technicalfiches.itemID', $id)
                    ->join('menuitems', 'technicalfiches.itemID', '=', 'menuitems.id')
                        ->join('products', 'technicalfiches.productID', '=', 'products.id')
                            ->groupBy(
                                'technicalfiches.itemID',
                                'technicalfiches.productID',
                                'menuitems.item_name',
                                'products.prod_name',
                                'technicalfiches.quantity',
                                'technicalfiches.cost',
                                'technicalfiches.fix_margin',
                                'technicalfiches.loss_margin',
                                'technicalfiches.variable_margin',
                                'products.prod_unmed'
                            )
                                ->get();

        return new Collection (
            $fiche
        );
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

    public function findFicheByItemId($id): Collection
    {
        return new Collection(
            Technicalfiche::where('itemID', $id)->get()
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
        $restaurant_data = $this->restaurantRepositoryInterface->find();
        if ($this->can_manage($request)):
            foreach ($productIds  as $key => $productId){
                $this->beforeSave($itemId, $productId);
                $productInfo = $this->productRepositoryInterface->findById($productId);
                $productCost = $this->stockRepositoryInterface->findLastProductEntry($productId);
                $cost = $productInfo->prod_unmed == "bt" ? $productCost->unitCost : ($quantitys[$key] * $productCost->unitCost) / $productInfo->prod_contain;
                $fiche = new Technicalfiche();
                $fiche->itemID = $itemId;
                $fiche->productID = $productId;
                $fiche->quantity = $quantitys[$key];
                $fiche->cost =  $cost;
                $fiche->fix_margin = $cost * $restaurant_data['fix_margin'] / 100;
                $fiche->loss_margin = $cost * $restaurant_data['loss_margin'] / 100;
                $fiche->variable_margin = $cost * $restaurant_data['variable_margin'] / 100;
                $fiche->save();

            }
            return;
        endif;
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
        $restaurant_data = $this->restaurantRepositoryInterface->find();
        if ($this->can_manage($request)):
            foreach ($products as $key => $product){
                $productInfo = $this->productRepositoryInterface->findById($product);
                $productCost = $this->stockRepositoryInterface->findLastProductEntry($product);
                $cost = $productInfo->prod_unmed == "bt" ? $productCost->unitCost : ($quantitys[$key] * $productCost->unitCost) / $productInfo->prod_contain;
                Technicalfiche::where([['itemID', $item_id], ['productID', $product]])
                    ->update([
                        'quantity' => $quantitys[$key],
                        'cost' => $cost,
                        'loss_margin' => $cost * $restaurant_data['loss_margin'] / 100,
                        'fix_margin' => $cost * $restaurant_data['fix_margin'] / 100,
                        'variable_margin' => $cost * $restaurant_data['variable_margin'] / 100
                    ]);
            }
            return;
        endif;
        throw new Exception("Você não tem permissão");
    }
}
