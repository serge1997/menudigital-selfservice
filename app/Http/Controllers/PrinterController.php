<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Main\Printer\PrinterRepositoryInterface;

class PrinterController extends Controller
{
    public function __construct(
        protected PrinterRepositoryInterface $printerRepositoryInterface
    ){}
}
