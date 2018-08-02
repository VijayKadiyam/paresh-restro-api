<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the tables of a hotel
   *
   *@
   */
  public function index()
  {
    $this->hotel = (new Hotel)->getHotel();
    $tables = optional($this->hotel)->tables;

    return response()->json([
      'data'  =>  $tables
    ]);
  }

  /*
   * To store a table
   *
   *@
   */ 
  public function store(Request $request)
  {
    $request->validate([
      'name'        =>  'required',
      'status_id'   =>  'required'
    ]); 

    $table = new Table($request->all());
    $table->store();
    $hotel = (new Hotel)->getHotel();
    $table = $hotel->tables()->find($table->id);

    return response()->json([
      'data'  =>  $table
    ], 201);
  }

  /*
   * To get a single table
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $table = $this->hotel->tables()->find($id);

    return response()->json([
      'data'  =>  $table
    ], 200);
  }

  /*
   * To update a table
   *
   *@
   */
  public function update(Request $request, $id)
  { 
    $request->validate([
      'status_id'   =>  'required'
    ]);  
    // To update all the table details
    $table = Table::find($id);
    $table->reform();
    $hotel = (new Hotel)->getHotel();
    $table = $hotel->tables()->find($table->id);

    return response()->json([
      'data'  =>  $table
    ], 200);
  }
}
