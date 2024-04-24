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

    public function printCustomerBill($orderItens)
    {
        $connector = new CupsPrintConnector("");
        $printer = new Printer($connector);

        $printer->setTextSize(1, 2);
        $printer->text(Restaurant::retrive()->rest_name); //restaurant name
        $printer->feed();
        $printer->feed();

        foreach ($orderItens as $key => $item) {
            //var_dump($item['item_name']); die;
            $printer->textRaw($item['item_name'] . '......'. $item['item_quantidade'] . '......'. $item['item_total']. "\n");
        }
        $printer->feed();
        $printer->textRaw("Thank you !\n");
        $printer->textRaw("\nTotal: ". array_sum($orderItens['item_total']). "\n");

        $printer->feed();
        $printer->feed();
        $printer->setTextSize(1, 1);
        $printer->textRaw(Restaurant::retrive()->rest_streetName. ', '. Restaurant::retrieve()->rest_StreetNumber . "\n");
        $printer->textRaw(Restaurant::retrive()->rest_name. "\n"); //put rest contact.
    }
}
