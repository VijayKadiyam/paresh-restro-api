<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  protected $fillable = [
    'hotel_id', 'subject_id', 'subject_type', 'address', 'state', 'state_code', 'pincode'
  ];

  /*
   * Get all the owning phone models
   *
   *@
   */
  public function subject()
  {
    return $this->morphTo();
  } 
}
