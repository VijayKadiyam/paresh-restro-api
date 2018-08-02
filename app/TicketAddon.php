<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketAddon extends Model
{
  protected $fillable = [
    'addon_menu_id', 'qty', 'description', 'status_id', 'amount'
  ];

  /*
   * To store a new ticket addon
   *
   *@
   */
  public function store($order, $ticket)
  {
    $ticket->ticket_addons()->save($this);
    $order->addToTotalAmount(request()->amount);

    return $this;
  }

  /*
   * To reform a order data
   *
   *@
   */
  public function reform($order, $ticket)
  {
    $order->removeFromTotalAmount($this->amount);
    $this->update(request()->all()); 
    $order->addToTotalAmount(request()->amount);

    return $this;
  }

  /*
   * A ticket addon belongs to ticket
   *
   *@
   */
  public function ticket()
  {
    return $this->belongsTo(Ticket::class);
  }

  /*
   * A ticket belongs to addon menu 
   *
   *@
   */
  public function addon_menu()
  {
    return $this->belongsTo(AddonMenu::class);
  }

  /*
   * A ticket belongs to a status
   *
   *@
   */
  public function status()
  {
    return $this->belongsTo(Status::class);
  }
}
