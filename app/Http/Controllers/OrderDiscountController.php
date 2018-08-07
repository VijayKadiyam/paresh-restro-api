<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderDiscount;
use Illuminate\Http\Request;

class OrderDiscountController extends Controller
{
  /*
   * To get all the order discounts of an order
   *
   *@
   */
  public function index(Order $order)
  {
    $orderDiscounts = $order->order_discounts;

    return response()->json([
      'data'  =>  $orderDiscounts
    ]);
  }

  /*
   * To store a order discount
   *
   *@
   */
  public function store(Request $request, Order $order)
  {
    $request->validate([
      'discount_type_id'=>  'required',
      'amount'          =>  'required' 
    ]); 

    $orderDiscount = new OrderDiscount($request->all());
    $orderDiscount->store($order);
    $orderDiscount = $order->order_discounts()->find($orderDiscount->id);

    return response()->json([
      'data'  =>  $orderDiscount
    ], 201);
  }

  /*
   * To get a single order discount
   *
   *@
   */
  public function show(Order $order, OrderDiscount $order_discount)
  {
    $orderDiscount = $order->order_discounts()->find($order_discount->id);

    return response()->json([
      'data'  =>  $orderDiscount
    ], 200);
  }

  /*
   *  TO update a order discount
   *
   *@
   */
  public function update(Request $request, Order $order, OrderDiscount $order_discount)
  {
    $request->validate([
      'discount_type_id'=>  'required',
      'amount'          =>  'required' 
    ]);  
    // To update all the order discount details
    $order_discount->reform($order);
    $orderDiscount = $order->order_discounts()->find($order_discount->id);

    return response()->json([
      'data'  =>  $orderDiscount
    ], 200);
  }
}
