<?php
namespace App\Main\QrcodeOrderNumber;

use App\Models\QrcodeOrderNumber;
use App\Traits\Permission;
use App\Main\QrcodeOrderNumber\Exception\QrCodeOrderNumberException;
use App\Models\OrderStatus;
use Exception;
use App\Models\Pedido;


class QrcodeOrderNumberRepository implements QrCodeOrderNumberRepositoryInterface
{
    use Permission;

    public function create($request)
    {
        if ( $this->can_manage($request) ) {
            $this->beforeSave($request->qrcode_order_number);
            return QrcodeOrderNumber::create($request->validated());
        }
        throw new Exception(__('messages.permission'));
    }

    public function beforeSave($qrcodeOrderNumber)
    {
        if ( QrcodeOrderNumber::where('qrcode_order_number', $qrcodeOrderNumber)->exists()) {
            throw new Exception("Qr code decode text {$qrcodeOrderNumber} already exists. Try again !");
        }
    }

    public function beforeCreateQrCodeOrder($qrCodeNumber)
    {
        if (
            Pedido::where([
                ['qrcode_order_number', $qrCodeNumber],
                ['status_id', OrderStatus::ANDAMENTO]
            ])->exists()
        ) {
            throw new Exception("Une commande existe dejá. rendez vous dans l'option addicioner nouveau élément ");
            exit();
        }
    }

    public function hasQrCodeNumber($qrCodeNumber) : bool
    {
        return QrcodeOrderNumber::where('qrcode_order_number', $qrCodeNumber)->exists();
    }
}
