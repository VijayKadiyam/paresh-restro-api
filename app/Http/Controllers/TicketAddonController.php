<?php

namespace App\Http\Controllers;

use App\Order;
use App\Ticket;
use App\TicketAddon;
use Illuminate\Http\Request;

class TicketAddonController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  /*
   * To get all the ticket addons
   *
   *@
   */
  public function index(Order $order, Ticket $ticket)
  {
    $ticketAddons = $ticket->ticket_addons;

    return response()->json([
      'data'  =>  $ticketAddons
    ]);
  }

  /*
   * To store a ticket addon
   *
   *@
   */
  public function store(Request $request, Order $order, Ticket $ticket)
  {
    $request->validate([
      'addon_menu_id'     =>  'required',
      'qty'               =>  'required', 
      'status_id'         =>  'required',
      'amount'            =>  'required' 
    ]); 

    $ticketAddon = new TicketAddon($request->all());
    $ticketAddon->store($order, $ticket);
    $ticketAddon = $ticket->ticket_addons()->find($ticketAddon->id);

    return response()->json([
      'data'  =>  $ticketAddon
    ], 201);
  }

  /*
   * To get a single ticket addon
   *
   *@
   */
  public function show(Order $order, Ticket $ticket, $ticketAddonId)
  {
    $ticketAddon = $ticket->ticket_addons()->find($ticketAddonId);

    return response()->json([
      'data'  =>  $ticketAddon
    ], 200);
  }

  /*
   * To update a ticket addon
   *
   *@
   */
  public function update(Request $request, Order $order, Ticket $ticket, $ticketAddonId)
  {
    $request->validate([
      'addon_menu_id'     =>  'required',
      'qty'               =>  'required', 
      'status_id'         =>  'required',
      'amount'            =>  'required' 
    ]); 

    // To update all the order details
    $ticketAddon = $ticket->ticket_addons()->find($ticketAddonId);
    $ticketAddon->reform($order, $ticket);
    $ticketAddon = $ticket->ticket_addons()->find($ticketAddon->id);

    return response()->json([
      'data'  =>  $ticketAddon
    ], 200);
  }
}
