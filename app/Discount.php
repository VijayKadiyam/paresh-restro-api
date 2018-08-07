<?php

namespace App;

use App\OrderDiscount;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
  protected $fillable = [
    'name', 'percent'
  ];

  /*
   * A discount has many discount orders
   *
   *@
   */
  public function order_discounts()
  {
    return $this->hasMany(OrderDiscount::class);
  }
}
