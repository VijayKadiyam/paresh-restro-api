<?php

namespace App\Http\Controllers;

use App\ContactType;
use Illuminate\Http\Request;

class ContactTypeController extends Controller
{
  /*
   * To fetch all the contact types
   *
   *@
   */
  public function index()
  { 
    $contactTypes = ContactType::latest()->get();

    return response()->json([
      'data'  =>  $contactTypes
    ]);
  }
}
