<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
  protected $fillable = [
    'name', 'description'
  ];

  /*
   * To store a new addon
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->addons()->save($this);

    return $this;
  }

  /*
   * To reform a addon data
   *
   *@
   */
  public function reform()
  {
    $this->update(request()->all()); 

    return $this;
  }

  /*
   * A addon belongs to a hotel
   *
   *@
   */
  public function hotel()
  {
    return $this->belongsTo(Hotel::class);
  }
}
