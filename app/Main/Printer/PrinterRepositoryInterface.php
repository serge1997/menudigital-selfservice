<?php
namespace App\Main\Printer;

use App\Models\Pedido;
use Illuminate\Database\Eloquent\Collection;

interface PrinterRepositoryInterface
{
    public function printCustomerBill(Collection $orderItens);
}
