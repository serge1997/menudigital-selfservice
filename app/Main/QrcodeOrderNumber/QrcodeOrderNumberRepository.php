<?php
namespace App\Main\QrcodeOrderNumber;

use App\Models\QrcodeOrderNumber;
use App\Traits\Permission;
use App\Main\QrcodeOrderNumber\Exception\QrCodeOrderNumberException;
use Exception;


class QrcodeOrderNumberRepository implements QrCodeOrderNumberRepositoryInterface
{
    use Permission;

    public function create($request)
    {
        if ( $this->can_manage($request) ) {
            $this->beforeSave($request->qrcode_order_number);
            return QrcodeOrderNumber::create($request->validated());
        }
        throw new Exception(__('message.permission'));
    }

    public function beforeSave($qrcodeOrderNumber)
    {
        if ( QrcodeOrderNumber::where('qrcode_order_number', $qrcodeOrderNumber)->exists()) {
            throw new Exception("Qr code decode text {$qrcodeOrderNumber} already exists. Try again !");
        }
    }
}
