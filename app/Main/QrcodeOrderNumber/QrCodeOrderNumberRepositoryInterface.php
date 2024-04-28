<?php
namespace App\Main\QrcodeOrderNumber;


interface QrCodeOrderNumberRepositoryInterface
{
    public function create($request);
    public function listAll();
    public function beforeSave($qrcodeOrderNumber);
    public function beforeCreateQrCodeOrder($qrCodeNumber);
    public function hasQrCodeNumber($qrCodeNumber) : bool;
}
