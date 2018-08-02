<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Recepie;
use Illuminate\Http\Request;

class RecepieController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the recepies of a hotel
   *
   *@
   */
  public function index()
  {
    $this->hotel = (new Hotel)->getHotel();
    $recepies = optional($this->hotel)->recepies;

    return response()->json([
      'data'  =>  $recepies
    ]);
  }

  /*
   * To store a new recepie
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'        =>  'required',
      'description' =>  'required'
    ]); 

    $recepie = new Recepie($request->all());
    $recepie->store();
    $hotel = (new Hotel)->getHotel();
    $recepie = $hotel->recepies()->find($recepie->id);

    return response()->json([
      'data'  =>  $recepie
    ], 201);
  }

  /*
   * To get a single recepie
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $recepie = $this->hotel->recepies()->find($id);

    return response()->json([
      'data'  =>  $recepie
    ], 200);
  }

  /*
   * To update a recepie
   *
   *@
   */
  public function update(Request $request, $id)
  { 
    $request->validate([
      'name'        =>  'required',
      'description' =>  'required'
    ]); 
    // To update all the recepie details
    $recepie = Recepie::find($id);
    $recepie->reform();
    $hotel = (new Hotel)->getHotel();
    $recepie = $hotel->recepies()->find($recepie->id);

    return response()->json([
      'data'  =>  $recepie
    ], 200);
  }
}
