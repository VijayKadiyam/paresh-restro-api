<?php

namespace App;

use App\OrderDiscount;
use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
  protected $fillable = [
    'type'
  ];  

  /*
   * A discount type has many order discounts
   *
   *@
   */
  public function order_discounts()
  {
    return $this->hasMany(OrderDiscount::class);
  }
}
