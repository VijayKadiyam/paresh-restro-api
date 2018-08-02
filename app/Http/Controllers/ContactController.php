<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Contact; 
use Illuminate\Http\Request;

class ContactController extends Controller
{
  protected $hotel;

  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the contacts of a hotel
   *
   *@
   */
  public function index()
  { 
    $this->hotel = (new Hotel)->getHotel();
    $contacts = optional($this->hotel)->contacts;

    return response()->json([
      'data'  =>  $contacts
    ]);
  }

  /*
   * To store a new contact in a hotel
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'company_name'  =>  'required',
      'name'          =>  'required',
      'pan_no'        =>  'required',
      'gstn_no'       =>  'required',
      'types'         =>  'required'
    ]); 

    $contact = new Contact($request->all());
    $contact->store(); 
    $hotel = (new Hotel)->getHotel();
    $contact = $hotel->contacts()->find($contact->id);

    return response()->json([
      'data'  =>  $contact
    ], 201);
  }

  /*
   * To get a single contact
   *
   *@
   */
  public function show($id)
  {
    $this->hotel = (new Hotel)->getHotel();
    $contact = $this->hotel->contacts()->find($id);

    return response()->json([
      'data'  =>  $contact
    ], 200);
  }

  /*
   * To update a contact
   *
   *@
   */
  public function update(Request $request, Contact $contact)
  {
    $request->validate([
      'company_name'  =>  'required',
      'name'          =>  'required',
      'pan_no'        =>  'required',
      'gstn_no'       =>  'required',
      'types'         =>  'required'
    ]); 
    
    // To update all the contact details
    $contact->reform();
    $hotel = (new Hotel)->getHotel();
    $contact = $hotel->contacts()->find($contact->id);

    return response()->json([
      'data'  =>  $contact
    ], 200);
  }
}
