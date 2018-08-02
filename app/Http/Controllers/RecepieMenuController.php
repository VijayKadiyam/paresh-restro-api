<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\MenuType;
use App\RecepieMenu;
use Illuminate\Http\Request;

class RecepieMenuController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the recepie menus of a hotel
   *
   *@
   */
  public function index()
  {
    $this->hotel = (new Hotel)->getHotel();
    $recepie_menus = optional($this->hotel)->recepie_menus;

    return response()->json([
      'data'  =>  $recepie_menus
    ]);
  }

  /*
   * To store a new recepie menu
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'recepie_id'  =>  'required',
      'type_id'     =>  'required',
      'price'        =>  'required'
    ]);

    $recepieMenu = new RecepieMenu($request->all());
    $recepieMenu->store();
    $hotel = (new Hotel)->getHotel();
    $recepieMenu = $hotel->recepie_menus()->find($recepieMenu->id);

    return response()->json([
      'data'  =>  $recepieMenu
    ], 201);
  }

  /*
   * To get a single recepie menu
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $recepieMenu = $this->hotel->recepie_menus()->find($id);

    return response()->json([
      'data'  =>  $recepieMenu
    ], 200);
  }

  /*
   * To update a recepie menu
   *
   *@
   */
  public function update(Request $request, RecepieMenu $recepieMenu)
  {
    $request->validate([
      'recepie_id'  =>  'required',
      'type_id'     =>  'required'
    ]); 
    // To update all the recepie menu details
    $recepieMenu->reform();
    $hotel = (new Hotel)->getHotel();
    $recepieMenu = $hotel->recepie_menus()->find($recepieMenu->id);

    return response()->json([
      'data'  =>  $recepieMenu
    ], 200);
  }
}
