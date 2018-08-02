<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
  protected $fillable = [
    'hotel_id', 'subject_id', 'subject_type', 'email'
  ];

  /*
   * Get all the owning email models
   *
   *@
   */
  public function subject()
  {
    return $this->morphTo();
  } 
}
