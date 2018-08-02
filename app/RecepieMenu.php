<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecepieMenu extends Model
{
  protected $fillable = [
    'recepie_id', 'type_id'
  ];

  /*
   * To store a new recepie menu
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->recepie_menus()->save($this);
    $this->addPrice();

    return $this;
  }

  /*
   * To reform a recepie menu data
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
   * A recepie menu has many recepie menu price
   *
   *@
   */
  public function prices()
  {
    return $this->hasMany(RecepieMenuPrice::class)
      ->latest();
  }

  /*
   * To update the price of recepie menu
   *
   *@
   */
  public function addPrice()
  {
    if(request()->price) {
      $recepieMenuPrice = new RecepieMenuPrice(request()->price);
      $price = $this->prices->count() ? $this->prices[0]->price : '';
      if($price != request()->price['price'])
        $this->prices()->save($recepieMenuPrice);
    }

    return $this;
  }

  /*
   * A recepie menu belongs to a recepie
   *
   *@
   */
  public function recepie()
  {
    return $this->belongsTo(Recepie::class);
  }

  /*
   * A recepie menu belongs to recepie menu type
   *
   *@
   */
  public function type()
  {
    return $this->belongsTo(MenuType::class);
  }
}
