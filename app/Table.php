<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
  use ImageTrait, MorphTrait;
  
  protected $fillable = [
    'name', 'capacity', 'status_id'
  ];

  /*
   * To store a new table
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->tables()->save($this);
    $this->storeImages($hotel, request()->images);

    return $this;
  }

  /*
   * To reform a table data
   *
   *@
   */
  public function reform()
  {
    $hotel = (new Hotel)->getHotel();
    $this->update(request()->all()); 
    $this->updateImages($hotel, request()->images);

    return $this;
  }

  /*
   * A table belongs to a hotel
   *
   *@
   */
  public function hotel()
  {
    return $this->belongsTo(Hotel::class);
  }

  /*
   * A table belongs to a status
   *
   *@
   */
  public function status()
  {
    return $this->belongsTo(Status::class);
  }

  /*
   * A table belongs to many orders
   *
   *@
   */
  public function orders()
  {
    return $this->belongsToMany(Order::class)
      ->withPivot('no_of_customers')
      ->with('tickets')
      ->latest();
  } 

  /*
   * If ordered through table then sync table_id and order_id
   *
   *@
   */
  public function syncOrder($order, $no_of_customers = 0)
  {
    $this->orders()->attach($order, [
      'no_of_customers' =>  $no_of_customers
    ]);

    return $this;
  }

  /*
   * TO unsync an order with a table
   *
   *@
   */
  public function unsyncOrder($order)
  {
    $this->orders()->detach($order);

    return $this;
  }
}
