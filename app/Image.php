<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
  protected $fillable = [
    'hotel_id', 'subject_id', 'subject_type', 'image_path'
  ];

  /*
   * Get all the owning image models
   *
   *@
   */
  public function subject()
  {
    return $this->morphTo();
  } 
}
