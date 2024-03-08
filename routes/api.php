<?php

use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BiController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\MealTypeController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TechnicalFicheController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PlanningController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('user-token', [UserController::class, 'currentuser']);

Route::middleware('auth:sanctum')->group(function() {

    Route::controller(MenuItemController::class)->group(function(){
        Route::post('menu-items', 'storeMenuItem');
        Route::get('menu-items', 'getAllMenu');
        Route::get('menu-items/{id}', 'findById');
        Route::delete('menu-items/{id}', 'deleteItemOnStatus');
        Route::put('menu-items', 'updatedMenuItem');
        Route::get('menu-items-search', 'searchMenuItem');
        Route::put('menu-items-rupture/{id}','setRuptureAction');
        Route::post('table', 'setTableNumber');
        Route::get('/checkcart/{checkTable?}', 'checkCart');
        Route::post('set/option/{id?}', 'SetCartOptions');
        Route::get('cart/items/{table}', 'CustomerFinalCart');
        Route::get('menu-items/fiche/{id}', 'showTechnicalFicheByMenuItemId');
        Route::get('cart-table/{table}', 'getNewCart');
    });

    //orders
    Route::controller(OrderController::class)->group(function() {
        Route::get('dashboard/tables', 'getTablesNumber');
        Route::post('order', 'confirmOrder');
        Route::get('orders-status', 'OperadorOrderList');
        Route::get('order-menu-itens/{id}', 'listOrderItens');
        Route::put('order-payment', 'orderPayment');
        Route::get('dashboard/cancel', 'getCanceledStatus');
        Route::post('new-item', 'postNewOrderItem');
        Route::get('order-history', 'getOrderHistory');
        Route::get('order-transfert-itens/{id}', 'listOrderTransfertItens');
        Route::post('order-transert-itens', 'createOrderTransfertAction');
        Route::get('order-itens-report', 'getOrderReportAction');
        Route::post('cancel/order-item', 'cancelOrderItemAction');
        Route::put('cancel-order', 'cancelOrderAction');
        Route::put('order-history/{order_id}', 'updateHistoryOrderStatusAction');
    });

    //BI
    ROute::controller(BiController::class)->group(function (){
        Route::get('/bi/general-stat', 'getGeneralStat');
        Route::get('/bi/dash-type-waiter', 'waiterStat');
        Route::get('cost-analyse', 'costQuery');
        Route::post('cost-analyse-filter', 'filterCostIntelligence');

    });

    //User
    Route::controller(UserController::class)->group(function() {
        Route::post('/create/user', 'create');
        Route::post('/edit-order/stat/{item_pedido}', 'EditOrderStat');
        Route::get('users', 'listAllAction');
        Route::put('user', 'updateAction');
        Route::delete('employee/{id}', 'deleteAction');
    });

    //stock
    Route::controller(StockController::class)->group(function() {
        Route::post('stock-entry', 'storeStockEntryAction');
        Route::get('stock-entry', 'listAllAction');
        Route::get('products-stat', 'get_stock_stat');
        Route::post('update/technical-fiche', 'Update_technical_fiche');
        Route::get('inventory', 'get_inventory');
        Route::put('reset-saldo', 'resetSaldoAction');
        Route::put("current/stock-rest", "cureentSaldoCheckAction");
        Route::get('stock-search', 'filterDataTableAction');
        Route::get('stock-requistion/{requisition_id}', 'listStockEntryByRequisition');
        Route::get('inventory-filter', 'listInventory');
        Route::delete('stock-delivery/requisition/{id}', 'deleteDeliveryByRequisitionIdAction');
        Route::delete('stock-delivery/requisition/{requisition_id}/product/{product_id}', 'deleteProductFromDeliveryAction');
        Route::post('stock-delivery/edit/product-quantity', 'updateProductDeliveryQuantityAction');
        Route::get('stock-devolution', 'findAllDevolutionAction');
        Route::get('stock-devolution/items/{requisition_id}', 'findDevolutionItemsByRequisitionIdAction');
    });

    //produto
    Route::controller(ProductController::class)->group(function(){
        Route::post('product', 'StoreProduct');
        Route::get('products', 'listAllProducts');
        Route::get('product/{id}', 'showProductToEdit');
        Route::put('product', 'update');
        Route::delete('product/{id}', 'delete');
    });

    //userRole
    Route::controller(UserRoleController::class)->group(function() {
        Route::get('rules', 'get_all_rules');
        Route::get('user-roles/{id}', 'listUserRoles');
        Route::post('role-delete/{id}', 'delete_user_role');
        Route::post('role/{id}', 'store_user_role');
    });

    //restuarant
    Route::controller(RestaurantController::class)->group(function() {
        Route::post('rest-info', 'create');
        Route::get('rest-info', 'index');
        Route::post('rest-logo', 'createLogo');
        Route::put('rest-info', 'update');
    });

    //supplier
    Route::controller(SupplierController::class)->group(function() {
        Route::post('supplier', 'StoreSupplier');
        Route::get('supplier', 'getSuppliers');
        Route::get('supplier/{id}', 'getToUpdate');
        Route::put('supplier', 'update');
        Route::delete('supplier/{id}', 'delete');
        Route::get('product-supplier/{id}', 'getProductSupplier');
    });

    //purchase requisition
    Route::controller(PurchaseRequisitionController::class)->group(function() {
        Route::post('purchase-requisition', 'createAction');
        Route::get('purchase-requisition', 'index');
        Route::get('purchase-requisition-show/{id}', 'show');
        Route::delete('purchase-requisition/{id}', 'deleteRequisition');
        Route::get('purchase-requisition/{id}', 'getRequisitionToUpdate');
        Route::get('purchase-requisition/search/{requisitionCode}', 'searchRequisitionCode');
        Route::post('purchase-requisition-product/quantity', 'updateRequisitionProductQuantity');
        Route::post('purchase-requisition/filter-item', 'getRequisitionProduct');
        Route::post('purchase-requisition/confirm', 'confirmPurchaseRequisition');
        Route::put('purchase-requisition-item/rejected/{requisition_id}/{product_id}', 'setRequisitionItemStatusToRejected');
    });

    //mealtype
    Route::controller(MealTypeController::class)->group(function() {
        Route::post('meal-type','createAction');
        Route::get('meal-type', 'getAllItemType');
        Route::get('meal-types/menu-items', 'listMealTypeByMenuItems');
        Route::get('meal-types/menu-items/filter/{id}', 'listMenuItemByMealType');
    });

    //Table
    Route::controller(TableController::class)->group(function() {
        Route::get('tablenumber', 'listFreeTable');
        Route::get('tablenumbers-orders', 'listTableByOrderStatus');
    });

    //carts
    Route::controller(CartController::class)->group(function() {
        Route::post('add-to-cart/{id}', 'AddItemdToCart');
        Route::put('cart-add/quantity/{id}', 'incrementItemQuantity');
        Route::put('cart-reduce/quantity/{id}', 'decrementItemQuantity');
        Route::delete('cart-item/{id}', 'deleteItemFromCart');
        Route::get('cart-itens/{table}', 'getCartItens');
    });

    //technical fiche
    Route::controller(TechnicalFicheController::class)->group(function() {
        Route::post('technical-fiche', 'createAction');
        Route::get('technical-fiche/{id}', 'showAction');
        Route::put('fiche-menu-item', 'addNewItemToItemFicheAction');
        Route::delete('fiche-menu-itens/products/{itemID}/{productID}', 'deleteProductFromItemFicheAction');
        Route::post('fiche-menu-itens/edit-quantity', 'editProductQuantityAction');
    });

    //reservation controller
    Route::controller(ReservationController::class)->group(function() {
        Route::post('reservation', 'createReservation');
        Route::get('reservation', 'index');
        Route::get('reservation/{id}', 'findById');
        Route::delete('reservation/{id}', 'deleteAction');
        Route::put('reservation', 'updateAction');
        Route::get('resource-teste', 'teste');
        Route::get('reservation-bi', 'reservationBiData');
        Route::put('reservation/auto-canceled', 'autoCancelReservationByDateAction');
        Route::put('reservation/{id}/status/{status}', 'updateReservationStatusAction');
    });

    //role
    Route::controller(RoleController::class)->group(function() {
        Route::get('roles', 'listAll');
    });

    //department
    Route::controller(DepartmentController::class)->group(function() {
        Route::get('departments', 'listAllAction');
    });

    //position
    Route::controller(PositionController::class)->group(function() {
        Route::get('positions', 'listAllAction');
        Route::put('user-position/{user_id}/{position_id}', 'updateByUserAction');
    });


    //planning
    Route::controller(PlanningController::class)->group(function() {
        Route::post('planning', 'createAction');
        Route::get('planning', 'listAllAction');
        Route::get('planning/user/{id}', 'findByHtmlIdAction');
        Route::delete('planning-user/{id}', 'deleteAction');
        Route::delete('planning', 'clearAction');
        Route::put('planning/{id}', 'updateAction');
    });
});

 //login
 Route::controller(LoginController::class)->group(function() {
    Route::post('login', 'loginAction');
    Route::post('logout', 'logoutAction')->middleware('auth:sanctum');
});

