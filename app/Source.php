<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
  /*
   * A source has many orders
   *
   *@
   */
  public function orders()
  {
    return $this->hasMany(Order::class);
  }
}
