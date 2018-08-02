<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
  protected $user;

  public function __construct()
  {
    $this->middleware('auth:api');
    $this->user = \Auth::guard('api')->user();
  }

  /*
   * To get all the hotels of a user
   *
   *@
   */
  public function index()
  {
    $hotels = $this->user->hotels;

    return response()->json([
      'data'  =>  $hotels
    ]);
  }

  /*
   * To store a new hotel
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'name'    =>  'required',
      'pan_no'  =>  'required',
      'gstn_no' =>  'required'
    ]);
    // Save hotel details
    $hotel = new Hotel($request->all());
    $hotel->store($this->user); 

    $hotel = $this->user->hotels()->find($hotel->id);

    return response()->json([
      'data'  =>  $hotel
    ], 201);
  }

  /*
   * To get a single hotel
   *
   *@
   */
  public function show($id)
  {
    $hotel = $this->user->hotels()->find($id);

    return response()->json([
      'data'  =>  $hotel
    ], 200);
  }

  /*
   * To update a hotel
   *
   *@
   */
  public function update(Request $request, Hotel $hotel)
  {
    $request->validate([
      'name'    =>  'required',
      'pan_no'  =>  'required',
      'gstn_no' =>  'required'
    ]);
    // To update all the hotel details
    $hotel->reform();
    $hotel = $this->user->hotels()->find($hotel->id);

    return response()->json([
      'data'  =>  $hotel
    ]); 
  }
}
