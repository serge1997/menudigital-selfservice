<?php

use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderTransfertController;
use App\Http\Controllers\BiController;
use App\Http\Controllers\StockController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SupplierController;

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

Route::get('/user-token', [UserController::class, 'currentUser']);


Route::controller(MenuItemController::class)->group(function(){
    Route::get('menu/type', 'getMealType');
    Route::post('save/meal', 'storeMenuItem');
    Route::get('menu/items', 'getMenu');
    Route::get('/cart-item/{id}/{table}', 'ShowCart');
    Route::get('menu-type', 'getMenuType');
    Route::get('item/type/{id_type?}', 'getItemOfType');
    Route::post('add/cart/{id}', 'addToCart');
    Route::post('table', 'setTableNumber');
    Route::get('/checkcart/{checkTable?}', 'checkCart');
    Route::post('set/option/{id?}', 'SetCartOptions');
    Route::post('add-quantity/{id}', 'AddQuantity');
    Route::post('reduce-quantity/{id}', 'ReduceQuantity');
    Route::get('cart/items/{table}', 'CustomerFinalCart');
    Route::get('delete/item/{cartID}/{table}', 'DeleteFromCart');
    Route::post('item-menu/update', 'updatedMenuItem');
    Route::get('edit/menu-item/{id}','getItemForEdit');
    Route::post('/set-rupture/{id}','SetRupture');
    Route::get('table','getTable');
    Route::post('/delete/menu-item/{id}','ToDelete');
    Route::post('save/type','SaveType');
    Route::get('/show/{id}', 'show');
    Route::get('cart-table/{table}', 'getNewCart');
});

//orders
Route::controller(PedidoController::class)->group(function() {
    Route::get('dashboard/tables', 'getTablesNumber');
    Route::post('order', 'confirmOrder');
    Route::get('/order/list/{table}', 'getOrderList');
    Route::get('dashboard/order', 'OperadorOrderList');
    Route::get('/dashboard/item/{id}', 'getOrderItem');
    Route::post('/update/status/{id}/{pedido}', 'UpdateOrderStatus');
    Route::get('get/bill/{id}', 'getBillItems');
    Route::get('dashboard/cancel', 'getCanceledStatus');
    Route::post('add-to-order/{id}', 'Add_To_Order');
    Route::post('new-item', 'postNewOrderItem');
    Route::get('bill-history', 'getBillHistory');
});

//Order Transfert
Route::controller(OrderTransfertController::class)->group(function() {
    Route::get('transfert/items/{id}', 'getTransfertOrderItems');
    Route::post('post/transfert/', 'postTransfert');
    //search
    Route::get('search', 'getSearchResult');
    //Report
    Route::get('dashboard/report','getReport');
});

//BI
ROute::controller(BiController::class)->group(function (){
    Route::get('/bi/general-stat/{start}/{end}', 'getGeneralStat');
    Route::get('/bi/dash-type-waiter/{start}/{end}', 'waiterStat');
});
Route::get('waiter/stats', [BiController::class, 'waiterStat']);

//User
Route::controller(UserController::class)->group(function() {
    Route::get('group', 'getGroup');
    Route::post('/create/user', 'create');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
    //Route::get('user-token','currentUser');
    Route::post('/cancel/order-item/{item_pedido}/{item_id}', 'CancelPermission');
    Route::post('cancel-order', 'cancelOrder');
    Route::post('/editorder/stat/{item_pedido}', 'EditOrderStat');
    Route::get('get/employe', 'getEmploye');
    Route::get('get/employe/{id}', 'getToupdateEmploye');
    Route::post('employe/delete/{id}', 'ToDeleteEmploye');
    Route::post('employe-status/update/{id}/{group_id}', 'updateEmployeStatus');
    Route::post('employe/update','EmployeUpdate');
});

//stock
Route::controller(StockController::class)->group(function() {
    Route::post('stock-entry', 'storeStockEntry');
    Route::post('technical-fiche', 'store_technical_fiche');
    Route::get('products-stat', 'get_stock_stat');
    Route::get('technical-fiche/{id}', 'show_technical_fiche');
    Route::post('update/technical-fiche', 'Update_technical_fiche');
    Route::get('inventory', 'get_inventory');
    Route::put('reset-saldo', 'resetSaldo');
    Route::put("current/stock-rest", "cureentSaldoCheck");
});

//produto
Route::controller(ProductController::class)->group(function(){
    Route::post('product', 'StoreProduct');
    Route::get('products', 'get_product');
    Route::get('product/{id}', 'showProductToEdit');
    Route::put('product', 'update');
    Route::delete('product/{id}', 'delete');
});

Route::controller(UserRoleController::class)->group(function() {
    Route::get('rules', 'get_all_rules');
    Route::post('role-delete/{id}', 'delete_user_role');
    Route::post('role/{id}', 'store_user_role');
});

Route::controller(RestaurantController::class)->group(function() {
    Route::post('rest-info', 'create');
    Route::get('rest-info', 'index');
    Route::post('rest-logo', 'createLogo');
    Route::put('rest-info', 'update');
});

Route::controller(SupplierController::class)->group(function() {
    Route::post('supplier', 'StoreSupplier');
    Route::get('supplier', 'getSuppliers');
    Route::get('supplier/{id}', 'getToUpdate');
    Route::put('supplier', 'update');
    Route::delete('supplier/{id}', 'delete');
});
