<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
  /*
   * To fetch all the currencies
   *
   *@
   */
  public function index()
  { 
    $currencies = Currency::latest()->get();

    return response()->json([
      'data'  =>  $currencies
    ]);
  }
}
