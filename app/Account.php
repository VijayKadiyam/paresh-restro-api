<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $fillable = [
    'hotel_id', 'subject_id', 'subject_type', 'acc_no', 'acc_name', 'ifsc_code', 'branch'
  ];

  /*
   * Get all the owning account models
   *
   *@
   */
  public function subject()
  {
    return $this->morphTo();
  } 
}
