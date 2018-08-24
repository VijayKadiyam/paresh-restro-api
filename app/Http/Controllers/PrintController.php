<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Items;
use App\Printing;
use Carbon\Carbon;
use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use Mike42\Escpos\EscposImage;
use App\Http\Controllers\OrderController;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;


class PrintController extends Controller
{
  protected $items = [];
  protected $order;
  protected $discount = 0;

  public function calculate(Request $request)
  {
    $hotel = Hotel::find($request->hotel_id);
    $this->order = $hotel->orders()->find($request->order_id);

    if($this->order->order_discounts->count()) {
      $this->discount = (float) $this->order->order_discounts[0]->amount;
    }

    foreach($this->order->tickets as $ticket) {
      $this->updateOrder($ticket->recepie_menu);
    }

    $this->index();

    $mytime = Carbon::now()->toDateTimeString();

    return $mytime;
  }

  /*
   * To update a order
   *
   *@
   */
  public function updateOrder($recepie_menu)
  {
    $alreadyItem = $this->checkAndUpdate($recepie_menu);
    if($alreadyItem) {

    }
    else {
      $item = new Printing;
      $item ->id = $recepie_menu->id;
      $item->name = $recepie_menu->recepie->name;
      $item->quantity = 1;
      $item->amount = $recepie_menu->prices[0]->price;
      $this->items[] = $item;
    }
  }

  /*
   * To checkAndUpdate if the recepie menu is already there or not
   *
   *@
   */
  public function checkAndUpdate($recepie_menu)
  {
    $check = 0;
    foreach($this->items as $item) {
      if($item->id == $recepie_menu->id) {
        $check = 1;
        $item->quantity += 1;
        $item->amount = $item->quantity * $recepie_menu->prices[0]->price;
      }
    }
    if($check) 
      return true;
    return false;
  }

  public function index()
  {
    // $connector = new CupsPrintConnector("EPSON_TM_T82_S_A");
    $connector = new WindowsPrintConnector("EPSON TM-T82 Receipt");
    /* Information for the receipt */
    $items = [];
    foreach($this->items as $item) {
      $items[] = new item($item->name, $item->amount, false, $item->quantity);
    }
    // $items = array(
    //     new item("Example item #1", "4.00", false, "5"),
    //     new item("Another thing", "3.50", false, "2"),
    //     new item("Something else", "1.00", false, "3"),
    //     new item("A final item", "4.45", false, "2"),
    // );
    $subtotal = new item('Subtotal', $this->order->total_amount, false, ' ');
    $discount = new item('Discount', $this->discount, false, ' ');
    $total = new item('Total', $this->order->total_amount - $this->discount, true);
    $inc = new item('', '(inc. GST)', false, ' ');
    /* Date is kept the same for testing */
    // $date = date('l jS \of F Y h:i:s A');
    $date = Carbon::now()->toDateTimeString();

    /* Start the printer */
    $logo = EscposImage::load("resources/logo.jpg", false);
    $printer = new Printer($connector);

    /* Print top logo */
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> graphics($logo);

    /* Name of shop */
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer -> text("Sohel's Foodee Paradise\n");
    $printer -> selectPrintMode();
    $printer -> text("Shop no. 2, Pharmed House, Walchand Hirachand Marg, Near CST Station, Fort, Mumbai, Maharashtra, 400 001\n");
    $printer -> feed();

    /* Title of receipt */
    $printer -> setEmphasis(true);
    $printer -> text("SALES INVOICE : ORDER NO-" . $this->order->id . "\n");
    $printer -> setEmphasis(false);

    /* Items */
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setEmphasis(true);
    $printer -> text(new item('Item', 'Rs', false, 'Qty'));
    $printer -> setEmphasis(false);
    foreach ($items as $item) {
        $printer -> text($item);
    }
    $printer -> setEmphasis(true);
    $printer -> text($subtotal);
    $printer -> setEmphasis(false);
    $printer -> feed();

    /* discount and total */
    $printer -> text($discount);
    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
    $printer -> text($total);
    $printer -> selectPrintMode();
    $printer -> text($inc);

    /* Footer */
    $printer -> feed(2);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("Thank you for visiting 'Sohel's Foodee Paradise'\n");
    $printer -> text("Please visit us again\n");
    $printer -> feed(2);
    $printer -> text("GSTN No:27AZVPB3638B1ZE FSSAI No:21518001000362\n");
    $printer -> text($date . "\n");

    /* Cut the receipt and open the cash drawer */
    $printer -> cut();
    $printer -> pulse();

    $printer -> close();
  }
}

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $quantity;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false, $quantity = null)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this->quantity = $quantity;
        $this -> dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 10;
        if($this->quantity && $this->name != "Total")
          $middleCols = 10;
        if($this->name == "Total")
          $leftCols = 38;
        else
          $leftCols = 28;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        if($this->quantity && $this->name != "Total")
          $middle = str_pad($this->quantity, $middleCols);
        else
          $middle = '';
        
        $sign = ($this -> dollarSign ? 'Rs ' : '');
        $right = str_pad($sign . $this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$middle$right\n";
    }
}

