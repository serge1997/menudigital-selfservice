<?php

use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderTransfertController;
use App\Http\Controllers\BiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('menu/type', [MenuItemController::class, 'getMealType']);
Route::post('/save/meal', [MenuItemController::class, 'StoreMenuItem']);
Route::get('/menu/items', [MenuItemController::class, 'getMenu']);
Route::get('get/cartitem/{id}/{table?}',[ MenuItemController::class, 'ShowCart']);
Route::get('get/menutype', [MenuItemController::class, 'getMenuType']);
Route::get('item/type/{id_type?}', [MenuItemController::class, 'getItemOfType']);
Route::post('add/cart/{id?}', [MenuItemController::class, 'addToCart']);
Route::post('table', [MenuItemController::class, 'setTableNumber']);
Route::get('/checkcart/{checkTable?}', [MenuItemController::class, 'checkCart']);
Route::post('set/option/{id?}', [MenuItemController::class, 'SetCartOptions']);
Route::post('add/quantity/{id}/{table}', [MenuItemController::class, 'AddQuantity']);
Route::post('reduce/quantity/{id}/{teste}', [MenuItemController::class, 'ReduceQuantity']);
Route::get('cart/items/{table}', [MenuItemController::class, 'CustomerFinalCart']);
Route::get('delete/item/{cartID}/{table}', [MenuItemController::class, 'DeleteFromCart']);

Route::get('edit/menu-item/{id}', [MenuItemController::class,'getItemForEdit']);
Route::post('/set-rupture/{id}', [MenuItemController::class, 'SetRupture']);
Route::post('/delete/menu-item/{id}', [MenuItemController::class,'ToDelete']);

Route::get('table', [MenuItemController::class, 'getTable']);
Route::get('dashboard/tables', [PedidoController::class, 'getTablesNumber']);
Route::post('save/type', [MenuItemController::class, 'SaveType']);
Route::get('/show/{id}', [MenuItemController::class, 'show']);

//orders

Route::post('order', [PedidoController::class, 'confirmOrder']);
Route::get('/order/list/{table}', [PedidoController::class, 'getOrderList']);
Route::get('dashboard/order', [PedidoController::class, 'OperadorOrderList']);
Route::get('/dashboard/item/{id}', [PedidoController::class, 'getOrderItem']);
Route::post('/update/status/{id}/{pedido}', [PedidoController::class, 'UpdateOrderStatus']);
Route::get('get/bill/{id}', [PedidoController::class, 'getBillItems']);
Route::get('dashboard/cancel', [PedidoController::class, 'getCanceledStatus']);

//Order Transfert

Route::get('transfert/items/{id}', [OrderTransfertController::class, 'getTransfertOrderItems']);
Route::post('post/transfert/', [OrderTransfertController::class, 'postTransfert']);

//search
Route::get('search', [OrderTransfertController::class,'getSearchResult']);

//Report
Route::get('dashboard/report', [OrderTransfertController::class, 'getReport']);

//BI
Route::get('waiter/stats', [BiController::class, 'waiterStat']);

//User
Route::get('group', [UserController::class, 'getGroup']);
Route::post('/create/user', [UserController::class, 'create']);
Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/dashboard/cancela/{item_pedido}/{item_id}', [UserController::class, 'CancelPermission']);
Route::post('/editorder/stat/{item_pedido}', [UserController::class, 'EditOrderStat']);

