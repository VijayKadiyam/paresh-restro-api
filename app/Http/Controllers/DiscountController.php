<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{ 
  /*
   * To fetch all the discounts
   *
   *@
   */
  public function index()
  { 
    $discounts = Discount::latest()->get();

    return response()->json([
      'data'  =>  $discounts
    ]);
  }

  /*
   * To store a new discount
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'    =>  'required',
      'percent' =>  'required' 
    ]);

    $discount = new Discount($request->all());
    $discount->save();

    return response()->json([
      'data'  =>  $discount->toArray()
    ], 201);
  }

  /*
   * To get a single discount
   *
   *@
   */
  public function show(Discount $discount)
  {
    return response()->json([
      'data'  =>  $discount
    ]);
  }

  /*
   * To update a discount
   *
   *@
   */
  public function update(Discount $discount, Request $request)
  {
    $discount->update($request->all());

    return response()->json([
      'data'  =>  $discount
    ]);
  } 
}
