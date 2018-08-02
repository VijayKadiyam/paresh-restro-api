<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
  /*
   * To fetch all the statuses
   *
   *@
   */
  public function index()
  { 
    $statuses = Status::latest()->get();

    return response()->json([
      'data'  =>  $statuses
    ]);
  }
}
