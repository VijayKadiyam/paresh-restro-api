<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
  /*
   * A status has many tables
   *
   *@
   */
  public function tables()
  {
    return $this->hasMany(Table::class);
  }
}
