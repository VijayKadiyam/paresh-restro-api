<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  protected $fillable = [
    'recepie_menu_id', 'qty', 'description', 'status_id', 'amount'
  ];

  /*
   * To store a new ticket
   *
   *@
   */
  public function store($order)
  {
    $order->tickets()->save($this);
    $order->addToTotalAmount(request()->amount);

    return $this;
  }

  /*
   * To reform a order data
   *
   *@
   */
  public function reform($order)
  {
    $order->removeFromTotalAmount($this->amount);
    $this->update(request()->all()); 
    $order->addToTotalAmount(request()->amount);

    return $this;
  }

  /*
   * A ticket belongs to an order
   *
   *@
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /*
   * A ticket belongs to recepie menu 
   *
   *@
   */
  public function recepie_menu()
  {
    return $this->belongsTo(RecepieMenu::class)
      ->with('prices', 'recepie', 'type')
      ->orderBy('type_id', 'ASC');
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

  /*
   * A ticket has many ticket addons
   *
   *@
   */
  public function ticket_addons()
  {
    return $this->hasMany(TicketAddon::class)
      ->with('ticket', 'ticket.order', 'addon_menu', 'status');
  }
}
