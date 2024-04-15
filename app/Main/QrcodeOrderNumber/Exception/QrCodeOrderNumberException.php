<?php
namespace App\Main\QrcodeOrderNumber\Exception;

use Exception;

final class QrCodeOrderNumberException extends Exception
{
    public static function duplicateQrcodeOrderNumber($qrcodeNumber): self
    {
        return new self ("The qr code order number {$qrcodeNumber} already exists. ");
    }
}
