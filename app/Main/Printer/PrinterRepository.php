<?php
namespace App\Main\Printer;

use App\Models\Pedido;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;

class PrinterRepository implements PrinterRepositoryInterface
{

    public function printCustomerBill(Collection $orderItens)
    {
        $printer = new CupsPrintConnector("");
        foreach ($orderItens as $key => $item) {


        }
    }
}
