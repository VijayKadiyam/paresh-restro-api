<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecepieMenuPrice extends Model
{
  protected $fillable = [
    'recepie_menu_id', 'price', 'currency_id'
  ];

  /*
   * A recepie menu price belongs to recepie menu
   *
   *@
   */
  public function recepie_menu()
  {
    return $this->belongsTo(RecepieMenu::class);
  }
}
