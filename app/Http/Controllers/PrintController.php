<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\Printer;

class PrintController extends Controller
{
  public function index()
  {
    $connector = new CupsPrintConnector("EPSON_TM_T82_S_A");
    $printer = new Printer($connector);

    $printer -> text("Hello World!\n");
    $printer -> cut();

    $printer -> close();
  }
}
