<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddonMenu extends Model
{
  protected $fillable = [
    'addon_id'
  ];

  /*
   * To store a new addon menu
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->addon_menus()->save($this);
    $this->addPrice();

    return $this;
  }

  /*
   * To reform a addon menu data
   *
   *@
   */
  public function reform()
  {
    $this->update(request()->all()); 
    $this->addPrice();

    return $this;
  }

  /*
   * A addon menu has many recepie menu price
   *
   *@
   */
  public function prices()
  {
    return $this->hasMany(AddonMenuPrice::class);
  }

  /*
   * To update the price of addon menu
   *
   *@
   */
  public function addPrice()
  {
    if(request()->price) {
      $recepieMenuPrice = new AddonMenuPrice(request()->price);
      $this->prices()->save($recepieMenuPrice);
    }

    return $this;
  }
}
