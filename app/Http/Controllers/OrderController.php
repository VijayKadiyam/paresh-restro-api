<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the orders of a hotel
   *
   *@
   */
  public function index(Request $request)
  {
    $this->hotel = (new Hotel)->getHotel();
    $orders = optional($this->hotel)->orders();

    if($request->fromDate && $request->toDate) {
      $orders->whereBetween('created_at', [
        $request->fromDate, $request->toDate
      ]);
    }

    $orders = $orders->get();

    return response()->json([
      'data'  =>  $orders
    ]);
  }

  /*
   * To store an order
   *
   *@
   */ 
  public function store(Request $request)
  {
    $request->validate([
      'source_id'    =>  'required' 
    ]); 

    $order = new Order($request->all());
    $order->store();
    $hotel = (new Hotel)->getHotel();
    $order = $hotel->orders()->find($order->id);

    return response()->json([
      'data'  =>  $order
    ], 201);
  }

  /*
   * To get a single order
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $order = $this->hotel->orders()->find($id);

    return response()->json([
      'data'  =>  $order
    ], 200);
  }

  /*
   * To update a order
   *
   *@
   */
  public function update(Request $request, $id)
  { 
    $request->validate([
      'source_id'    =>  'required' 
    ]);  
    // To update all the order details
    $order = Order::find($id);
    $order->reform();
    $hotel = (new Hotel)->getHotel();
    $order = $hotel->orders()->find($order->id);

    return response()->json([
      'data'  =>  $order
    ], 200);
  }

  /*
   * To get the orders between specific dates
   *
   *@
   */
  public function orderReport()
  {
    
  }
}
