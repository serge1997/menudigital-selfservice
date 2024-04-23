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
        $connector = new CupsPrintConnector("");
        $printer = new Printer($connector);

        $printer->setTextSize(1, 2);
        $printer->text(Restaurant::retrive()->rest_name); //restaurant name
        $printer->feed();
        $printer->feed();

        foreach ($orderItens as $key => $item) {
            $printer->textRaw($item['item_name']);
        }
    }
}
