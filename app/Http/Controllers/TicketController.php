<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Order;
use App\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the tickets of an order
   *
   *@
   */
  public function index(Order $order)
  {
    $tickets = $order->tickets;

    return response()->json([
      'data'  =>  $tickets
    ]);
  }

  /*
   * To store a ticket
   *
   *@
   */
  public function store(Request $request, Order $order)
  {
    $request->validate([
      'recepie_menu_id'   =>  'required',
      'qty'               =>  'required', 
      'status_id'         =>  'required',
      'amount'            =>  'required' 
    ]); 

    $ticket = new Ticket($request->all());
    $ticket->store($order);
    $ticket = $order->tickets()->find($ticket->id);

    return response()->json([
      'data'  =>  $ticket
    ], 201);
  }

  /*
   * To get a single ticket
   *
   *@
   */
  public function show(Order $order, Ticket $ticket)
  {
    $ticket = $order->tickets()->find($ticket->id);

    return response()->json([
      'data'  =>  $ticket
    ], 200);
  }

  /*
   *  TO update a ticket
   *
   *@
   */
  public function update(Request $request, Order $order, Ticket $ticket)
  {
    $request->validate([
      'recepie_menu_id'   =>  'required',
      'qty'               =>  'required', 
      'status_id'         =>  'required',
      'amount'            =>  'required' 
    ]);  
    // To update all the order details
    $ticket->reform($order);
    $ticket = $order->tickets()->find($ticket->id);

    return response()->json([
      'data'  =>  $ticket
    ], 200);
  }

  /*
   * To delete a ticket
   *
   *@
   */
  public function destroy(Order $order, Ticket $ticket)
  {
    $order->removeFromTotalAmount($ticket->amount);
    $ticket->delete();

    return response()->json([], 200);
  }
}
