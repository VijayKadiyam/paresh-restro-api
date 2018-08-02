<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recepie extends Model
{

  use ImageTrait, MorphTrait;

  protected $fillable = [
    'name', 'description'
  ];

  /*
   * To store a new recepie
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->recepies()->save($this);
    $this->storeImages($hotel, request()->images);

    return $this;
  }

  /*
   * To reform a recepie data
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
   * A recepie belongs to a hotel
   *
   *@
   */
  public function hotel()
  {
    return $this->belongsTo(Hotel::class);
  }
}
