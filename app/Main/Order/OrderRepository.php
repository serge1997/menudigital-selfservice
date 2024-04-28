<?php
namespace App\Main\Order;

use App\Models\Pedido;
use App\Models\ItensPedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\PaiementType;
use App\Http\Services\UserInstance;
use App\Models\Menuitems;
use App\Models\Role;
use App\Http\Services\Stock\StockServiceRepository;
use App\Main\User\UserRepositoryInterface;
use App\Main\TechnicalFiche\TechnicalFicheRepositoryInterface;
use App\Http\Services\Util\Util;
use App\Models\Cart;
use App\Models\OrderStatus;
use App\Events\CancelOrder;
use App\Models\Saldo;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Traits\Permission;
use App\Traits\AuthSession;
use App\Models\Restaurant;
use DateTime;
use App\Http\Resources\PedidoResource;
use Carbon\Carbon;
use App\Main\Printer\PrinterRepositoryInterface;
use App\Main\QrcodeOrderNumber\QrCodeOrderNumberRepositoryInterface;


class OrderRepository implements OrderRepositoryInterface
{
    use Permission, AuthSession { AuthSession::autth insteadof Permission; }

    public array $Order_item_ids = [];
    public array $Order_item_quantitys = [];

    public function __construct(
        protected UserRepositoryInterface $userRepositoryInterface,
        protected TechnicalFicheRepositoryInterface $technicalFicheRepositoryInterface,
        protected PrinterRepositoryInterface $printerRepositoryInterface,
        protected QrcodeOrderNumberRepositoryInterface $qrcodeOrderNumberRepositoryInterface
    ){}

    public function getOrders(): Collection
    {
        $orders = Pedido::select(
                'pedidos.id',
                'pedidos.ped_customerName',
                'pedidos.ped_tableNumber',
                'status.stat_desc',
                'pedidos.status_id',
                'pedidos.qrcode_order_number',
                DB::raw('SUM(itens_pedido.item_total) AS total')
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('status', 'pedidos.status_id', '=', 'status.id')
                        ->where([['pedidos.status_id', PaiementType::PROGRESS], ['ped_delete', false]])
                            ->groupBy(
                                'pedidos.id',
                                'pedidos.ped_customerName',
                                'pedidos.ped_tableNumber',
                                'itens_pedido.item_pedido',
                                'pedidos.status_id',
                                'status.stat_desc',
                                'pedidos.qrcode_order_number'
                                    )
                                    ->orderByRaw('pedidos.created_at DESC')
                                        ->get();

       return new Collection(
            $orders
        );
    }

    public function getOrderItens($id): Collection
    {
        $orderItens = Pedido::select(
                'pedidos.id',
                'pedidos.status_id',
                'itens_pedido.item_id',
                'menuitems.item_name',
                'itens_pedido.item_quantidade',
                'itens_pedido.item_total',
                'itens_pedido.item_price'
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where('pedidos.id', $id)
                            ->get();

        return new Collection($orderItens);
    }

    public function setOrderPaymentStatus($request)
    {
        if ($this->can_manage($request) || $this->can_cashier($request)):
            $order = $request->order;
            $status = $request->status;
            DB::table('pedidos')
                ->where('id', $order)
                ->update([
                    'status_id' => $status
                ]);
            return true;
        endif;
        throw new Exception(__('messages.permission'));

    }

    public function getOrderHistory($request): Collection
    {
        $where = "";
        if ($request->start && $request->end){
            $where .= "pedidos.ped_emissao >= '".substr($request->start, 0, 10)."' AND pedidos.ped_emissao <= '". substr($request->end, 0, 10)."'";
        }
        // var_dump($where); die;
        $orderHistory = DB::table('pedidos')
        ->select(
            'pedidos.id',
            'pedidos.ped_customerName',
            'users.name',
            'pedidos.ped_tableNumber',
            'status.stat_desc',
            DB::raw("DATE_FORMAT(pedidos.ped_emissao, '%d/%m/%Y') as ped_emissao"),
            DB::raw('SUM(item_total) as total')
        )
            ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                ->join('users', 'pedidos.user_id', '=', 'users.id')
                    ->join('status', 'pedidos.status_id', '=', 'status.id')
                        ->where([['pedidos.status_id', '<>', 6], ['pedidos.status_id', '<>', 5]])
                            ->whereRaw($where)
                                ->groupBy(
                                    'id',
                                    'pedidos.ped_customerName',
                                    'users.name',
                                    'status.stat_desc',
                                    'pedidos.ped_tableNumber',
                                    'pedidos.ped_emissao'
                                )
                                    ->orderBy('pedidos.ped_emissao', 'DESC')
                                        ->get();

        return new Collection($orderHistory);
    }

    public function addNewItemToOrder($request)
    {
        $tableNumber = null;
        $orderID = $request->orderID;
        $itemID = $request->itemID;
        $quantity = $request->quantity;
        $qrCode =  $request->qrcode_order_number;
        $newItem = $this->findOrderByQrCodeNumber($qrCode, $itemID);
        StockServiceRepository::SetItemSaldoZeroException($tableNumber, $request->itemID);
        StockServiceRepository::checkSetItemSaldoZeroAddItemToOrder($request->itemID);
        StockServiceRepository::StockOutProduct(str_split($itemID, 10), str_split($quantity, 10));

        if ( $this->orderInWaitingStatus($qrCode) ) {

            if (ItensPedido::where([['item_pedido', $newItem->item_pedido], ['item_id', $itemID]])->exists()){

                $totalQuantity = $newItem->item_quantidade + $quantity;
                ItensPedido::where([
                    ['item_pedido', $newItem->item_pedido],
                    ['item_id', $itemID],
                    ['item_delete', false]
                ])
                ->update([
                    'item_quantidade' => $totalQuantity,
                    'item_total' => $newItem->item_price * $totalQuantity
                ]);
                StockServiceRepository::ControleItemLowStockRuptured(str_split($itemID, 10));
            } else {
                $menuitem = Menuitems::where('id', $itemID)->first();
                $itens = new ItensPedido();
                $itens->item_pedido     = $newItem->item_pedido;
                $itens->item_id         = $itemID;
                $itens->item_quantidade = $quantity;
                $itens->item_price      = $menuitem->item_price;
                $itens->item_total      = $menuitem->item_price * $quantity;
                $itens->item_emissao    = Util::Today();
                $itens->save();
                StockServiceRepository::ControleItemLowStockRuptured(str_split($itemID, 10));
            }
        }
    }

    public function createOrder($request)
    {
        $menuitem = null;
        if ( $this->qrcodeOrderNumberRepositoryInterface->hasQrCodeNumber($request->qrcode_order_number)){
            $this->qrcodeOrderNumberRepositoryInterface->beforeCreateQrCodeOrder($request->qrcode_order_number);
            StockServiceRepository::SetItemSaldoZeroException($request->ped_tableNumber, $menuitem);
            $orderItem = Cart::where('tableNumber', $request->ped_tableNumber)
                ->get();
                if (
                    $this->can_manage($request) ||
                    $this->can_take_order($request) ||
                    $this->can_cashier($request)
                ):
                    $order = new Pedido();
                    $order->ped_tableNumber  = $request->ped_tableNumber;
                    $order->ped_customerName = $request->ped_customerName;
                    $order->user_id          = $this->autth($request);
                    $order->ped_emissao      = Util::Today();
                    $order->status_id        = 6;
                    $order->qrcode_order_number = $request->qrcode_order_number;
                    $order->ped_customer_quantity = $request->ped_customer_quantity;
                    $order->save();

                    foreach($orderItem as $itemPedido) {
                        $itens = new ItensPedido();
                        $itens->item_emissao    = Util::Today();
                        $itens->item_pedido     = $order->id;
                        $itens->item_quantidade = $itemPedido->quantity;
                        $itens->item_id         = $itemPedido->item_id;
                        $itens->item_price      = $itemPedido->unit_price;
                        $itens->item_total      = $itemPedido->total;
                        $itens->item_comments   = $itemPedido->comments;
                        $itens->item_option     = $itemPedido->options;
                        $itens->save();
                        $this->Order_item_ids[]       = $itemPedido->item_id;
                        $this->Order_item_quantitys[] = $itemPedido->quantity;
                    }
                    //$this->printerRepositoryInterface->printCustomerBill($this->getOrderItens($order->id));
                    StockServiceRepository::StockOutProduct($this->Order_item_ids, $this->Order_item_quantitys);
                    StockServiceRepository::ControleItemLowStockRuptured($this->Order_item_ids);
                    DB::table('carts')->where('tableNumber', $request->ped_tableNumber)
                        ->delete();
                    return ;
                endif;
            Cart::where('tableNumber', $request->ped_tableNumber)
                ->delete();
            throw new Exception(__('messages.permission'));
            exit();
        }
        throw new Exception('Qr code inconnu');
    }

    public function getOrderTransfert($id): Collection
    {
        $transfertItems = DB::table('itens_pedido')
                ->select(
                    'itens_pedido.item_pedido',
                    'itens_pedido.item_id',
                    'itens_pedido.item_quantidade',
                    'menuitems.item_name'
                )
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where([['item_pedido', $id], ['item_quantidade', '>', 0]])
                            ->get();

        return new Collection($transfertItems);
    }

    public function createTransertItensAction($request)
    {
        $items_id = $request->item_id;
        $item_quantidade = $request->item_quantidade;

        $waiter = Pedido::where('id', $request->item_pedido)
            ->first();

        /**
         * fazer a media da caluna quantidade cliente do dia
         *  para setar na quantidade de pessoa dos pedido transferido
         * */
        $quantity_customer_transfert = Pedido::where('ped_emissao', $waiter->ped_emissao)
            ->avg('ped_customer_quantity');


        $auth = $request->session()->get('auth-vue');
        foreach (
            UserInstance::get_user_roles($auth) as $trasnfert
        ):
            if ($trasnfert->role_id === Role::MANAGER ||
                $trasnfert->role_id === Role::CAN_USE_CASHIER
            ):

                $order = new Pedido();
                $order->ped_tableNumber = $request->ped_tableNumber;
                $order->ped_customerName = "Transfered";
                $order->user_id = $waiter->user_id;
                $order->ped_emissao = Util::Today();
                $order->status_id = 6;
                $order->ped_customer_quantity = $quantity_customer_transfert;
                $order->save();

                foreach ($items_id as $key=>$ids):
                    $item = ItensPedido::where('item_pedido', $waiter->id)->where('item_id', $ids)
                        ->first();
                        //foreach ($item_quantidade as $qty):
                            $qty = $item_quantidade[$key];
                            if ($item->item_quantidade == $qty):
                                DB::table('itens_pedido')
                                    ->where('item_pedido', $request->item_pedido)
                                        ->where('item_id', $ids)
                                            ->update([
                                                'item_pedido' => $order->id,
                                            ]);
                            else:

                                if ($qty > $item->item_quantidade):
                                    DB::table('pedidos')
                                        ->where('id', $order->id)
                                            ->delete();

                                        throw new Exception(__('messages.transfert_order_quantity_exception'));
                                endif;
                                DB::table('itens_pedido')
                                    ->where('item_pedido', $request->item_pedido)
                                    ->where('item_id', $ids)
                                        ->update([
                                            'item_quantidade' => $item->item_quantidade - $qty,
                                            'item_total' => $item->item_price * ($item->item_quantidade - $qty)
                                        ]);

                                $tintens = new ItensPedido();
                                $tintens->item_emissao = $item->item_emissao;
                                $tintens->item_quantidade = $qty;
                                $tintens->item_id = $item->item_id;
                                $tintens->item_price = $item->item_price;
                                $tintens->item_total = $item->item_price * $qty;
                                $tintens->item_comments = $item->item_comments;
                                $tintens->item_option = $item->item_option;
                                $tintens->item_pedido = $order->id;
                                $tintens->save();
                            endif;
                        //endforeach;
                endforeach;
                return;
            endif;
        endforeach;
        throw new Exception(__('messages.permission'));
    }

    public function getOrdersReport()
    {
        $open = "";
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $restaurant = Restaurant::find(Restaurant::RESTAURANT_KEY);
        $latestOrder = Pedido::select('*')->latest()->first();
        $latestOrderDate = date('Y-m-d', strtotime("$latestOrder->created_at"));
        $latesTime = substr($latestOrder->created_at, 11, 2);
        if (in_array($latesTime, ['00', '01', '02', '03', '04', '05', '06'])){
            $open .= date('Y-m-d', strtotime("{$latestOrderDate} - 1 day")). ' '. $restaurant->res_open;
        }else{
            $open .= $today. ' '. $restaurant->res_open;
        }

        $report = DB::table('itens_pedido')
            ->select(
                'menuitems.item_name',
                DB::raw('SUM(itens_pedido.item_quantidade) AS quantidade'),
                DB::raw('SUM(.itens_pedido.item_total) AS total')
            )
                ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                    ->join('pedidos', 'itens_pedido.item_pedido', '=', 'pedidos.id')
                        ->where([
                                ['itens_pedido.item_delete', false],
                                ['pedidos.status_id', '<>', 6],
                            ])
                                ->whereBetween('pedidos.created_at', [$open, $latestOrder->created_at])
                                    ->groupby(
                                        'menuitems.item_name'
                                    )->get();

        $paiement_data = DB::table('pedidos')
            ->select(DB::raw('SUM(itens_pedido.item_total) cash'), 'status.stat_desc')
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->rightJoin('status', 'pedidos.status_id', '=', 'status.id')
                        ->where([
                            ['itens_pedido.item_delete', false],
                        ])
                            ->whereBetween('pedidos.created_at', [$open, $latestOrder->created_at])
                                ->groupBy([
                                    'status.stat_desc'
                                ])->get();

        $itemCanceled = DB::table('itens_pedido')
                ->select(DB::raw(' SUM(item_price * item_quantidade) valor'))
                    ->where([
                        ['item_delete', 1],
                    ])
                        ->whereBetween('itens_pedido.created_at', [$open, $latestOrder->created_at])
                            ->get();
        return [
            'report' => $report,
            'paiment' => $paiement_data,
            'valcanceled' => $itemCanceled
        ];
    }

    public function returnItem($item_id, $quantidade): void
    {
        $fiches = $this->technicalFicheRepositoryInterface->findByItemId($item_id);
        foreach ($fiches as $fiche) {
            $saldo = Saldo::where('productID', $fiche['productID'])->first();
            Saldo::where('productID', $fiche['productID'])
                ->update([
                    'saldoFinal' => $saldo->saldoFinal + ($fiche['quantity'] * $quantidade)
                ]);
        }

    }


    public function cancelOrderItem($request)
    {
        $item_pedido = $request->item_pedido;
        $item_id = $request->item_id;
        $password = $request->password;
        $item_quantidade = $request->quantidade;
        $to_return = $request->to_return;

        $password = User::where('position_id', User::GERENTE)->first();
        $item = ItensPedido::where([['item_pedido', $item_pedido], ['item_id', $item_id], ['item_quantidade', '>=', 1]])
                ->first();

        if (Hash::check($request->password, $password->password)):
            if ($item_quantidade == $item->item_quantidade):
                DB::table('itens_pedido')
                    ->where('item_pedido', $item_pedido)
                        ->where('item_id', $item_id)
                            ->update([
                                'item_delete'=> true,
                                'item_quantidade' => $item->item_quantidade * (-1),
                                'item_total' => $item->item_total * 0
                            ]);
            else:
                DB::table('itens_pedido')
                    ->where([['item_pedido', $item_pedido], ['item_id', $item_id], ['item_quantidade', '>=', 1]])
                        ->update([
                            'item_quantidade' => $item->item_quantidade - $item_quantidade,
                            'item_total' => $item->item_total - ($item_quantidade * $item->item_price)
                        ]);

                $AddCanceledItem = new ItensPedido();
                $AddCanceledItem->item_pedido = $item_pedido;
                $AddCanceledItem->item_id = $item_id;
                $AddCanceledItem->item_quantidade = $item_quantidade * (-1);
                $AddCanceledItem->item_total = $item->item_total * 0;
                $AddCanceledItem->item_delete = true;
                $AddCanceledItem->item_price = $item->item_price;
                $AddCanceledItem->item_emissao = $item->item_emissao;
                $AddCanceledItem->item_option = $item->item_option;
                $AddCanceledItem->save();
            endif;
            event(new CancelOrder($item));
            if ($to_return){
                $this->returnItem($item_id, $item_quantidade);
            }
            return;
        endif;
        throw new Exception("password incorrect");
    }

    public function cancelOrder($request)
    {
        $password = User::where('id', User::GERENTE)
            ->first();

        if (Hash::check($request->password, $password->password)):
            DB::table('pedidos')
                ->where('id', $request->orderID)
                    ->update([
                        'ped_delete' => 1,
                        'status_id' => 5
                    ]);
            return;
        endif;
        throw new Exception("password incorrect ");
    }

    /**
     * @Method PUT
     * @param $order_id
     * @param $request
     * update order status in order history page
     */
    public function updateHistoryOrderStatus($order_id, $request): void
    {
        $password = User::where('id', User::GERENTE)
            ->first();
        if ($request->status_id != PaiementType::CANCELED && $request->status_id != PaiementType::PROGRESS):
            if (Hash::check($request->password, $password->password) && $this->can_manage($request)):
                Pedido::where('id', $order_id)
                    ->update([
                        'status_id' => $request->status_id
                    ]);
                return;
            endif;
        else:
            throw new Exception(__('messages.order_history_update'));
        endif;
        throw new Exception(__('messages.permission'));
    }

    public function findByQrCodeOrderNumber(int $qrcode_order_number)
    {
       return new Collection(
            Pedido::select(
                'pedidos.id',
                'pedidos.status_id',
                'itens_pedido.item_id',
                'menuitems.item_name',
                'itens_pedido.item_quantidade',
                'itens_pedido.item_total',
                'itens_pedido.item_price'
            )
                ->join('itens_pedido', 'pedidos.id', '=', 'itens_pedido.item_pedido')
                    ->join('menuitems', 'itens_pedido.item_id', '=', 'menuitems.id')
                        ->where([['pedidos.qrcode_order_number', $qrcode_order_number], ['pedidos.status_id', OrderStatus::ANDAMENTO]])
                            ->get()
            );
    }

    public function orderInWaitingStatus($qrcode_order_number): bool
    {
        return Pedido::where([
            ['qrcode_order_number', $qrcode_order_number],
            ['status_id', OrderStatus::ANDAMENTO],
            ['ped_emissao', Carbon::now()->isoFormat('Y-MM-DD')]
        ])->exists();
    }

    public function findOrderByQrCodeNumber($qrcode_order_number, $item_id)
    {
        $order = Pedido::where([
            ['qrcode_order_number', $qrcode_order_number],
            ['status_id', OrderStatus::ANDAMENTO],
            ['ped_emissao', Carbon::now()->isoFormat('Y-MM-DD')]
        ])->first();

        if ($order->qrcode_order_number) {
            return ItensPedido::where([
                ['item_pedido', $order->id],
            ])->first();
        }
        throw new Exception("Aucune commande lancé avec ce Qr code");
    }
}
