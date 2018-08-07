<?php

namespace App\Http\Controllers;

use App\DiscountType;
use Illuminate\Http\Request;

class DiscountTypeController extends Controller
{
  /*
   * To fetch all the discount types
   *
   *@
   */
  public function index()
  { 
    $types = DiscountType::latest()->get();

    return response()->json([
      'data'  =>  $types
    ]);
  }

  /*
   * To store a new discount type
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'type'  =>  'required'
    ]);

    $type = new DiscountType($request->all());
    $type->save();

    return response()->json([
      'data'  =>  $type->toArray()
    ], 201);
  }

  /*
   * To get a single discount type
   *
   *@
   */
  public function show(DiscountType $discount_type)
  {
    return response()->json([
      'data'  =>  $discount_type
    ]);
  }

  /*
   * To update a discount type
   *
   *@
   */
  public function update(DiscountType $discount_type, Request $request)
  {
    $discount_type->update($request->all());

    return response()->json([
      'data'  =>  $discount_type
    ]);
  } 
}
