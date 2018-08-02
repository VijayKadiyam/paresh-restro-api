<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\AddonMenu;
use Illuminate\Http\Request;

class AddonMenuController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the addon menus of a hotel
   *
   *@
   */
  public function index()
  {
    $this->hotel = (new Hotel)->getHotel();
    $addon_menus = optional($this->hotel)->addon_menus;

    return response()->json([
      'data'  =>  $addon_menus
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
      'addon_id'    =>  'required',
      'price'       =>  'required'
    ]);

    $addonMenu = new AddonMenu($request->all());
    $addonMenu->store();
    $hotel = (new Hotel)->getHotel();
    $addonMenu = $hotel->addon_menus()->find($addonMenu->id);

    return response()->json([
      'data'  =>  $addonMenu
    ], 201);
  }

  /*
   * To get a single addon menu
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $addonMenu = $this->hotel->addon_menus()->find($id);

    return response()->json([
      'data'  =>  $addonMenu
    ], 200);
  }

  /*
   * To update a addon menu
   *
   *@
   */
  public function update(Request $request, AddonMenu $addonMenu)
  {
    $request->validate([
      'addon_id'  =>  'required'
    ]); 
    // To update all the addon menu details
    $addonMenu->reform();
    $hotel = (new Hotel)->getHotel();
    $addonMenu = $hotel->addon_menus()->find($addonMenu->id);

    return response()->json([
      'data'  =>  $addonMenu
    ], 200);
  }
}
