<?php

namespace App\Http\Controllers;

use App\MenuType;
use Illuminate\Http\Request;

class MenuTypeController extends Controller
{
  /*
   * To fetch all the menu types
   *
   *@
   */
  public function index()
  { 
    $menuTypes = MenuType::latest()->get();

    return response()->json([
      'data'  =>  $menuTypes
    ]);
  }
}
