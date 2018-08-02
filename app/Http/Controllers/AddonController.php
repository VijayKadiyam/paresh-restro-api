<?php

namespace App\Http\Controllers;

use App\Addon;
use App\Hotel;
use Illuminate\Http\Request;

class AddonController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the addons of a hotel
   *
   *@
   */
  public function index()
  {
    $this->hotel = (new Hotel)->getHotel();
    $addons = optional($this->hotel)->addons;

    return response()->json([
      'data'  =>  $addons
    ]);
  }

  /*
   * To store a new addon
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'        =>  'required',
      'description' =>  'required'
    ]); 

    $addon = new Addon($request->all());
    $addon->store();

    return response()->json([
      'data'  =>  $addon
    ], 201);
  }

  /*
   * To get a single addon
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $addon = $this->hotel->addons()->find($id);

    return response()->json([
      'data'  =>  $addon
    ], 200);
  }

  /*
   * To update a addon
   *
   *@
   */
  public function update(Request $request, $id)
  { 
    $request->validate([
      'name'        =>  'required',
      'description' =>  'required'
    ]); 
    // To update all the addon details
    $addon = Addon::find($id);
    $addon->reform();
    $hotel = (new Hotel)->getHotel();
    $addon = $hotel->addons()->find($addon->id);

    return response()->json([
      'data'  =>  $addon
    ], 200);
  }
}
