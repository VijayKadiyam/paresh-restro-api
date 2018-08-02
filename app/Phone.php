<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
  protected $fillable = [
    'hotel_id', 'subject_id', 'subject_type', 'phone'
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
