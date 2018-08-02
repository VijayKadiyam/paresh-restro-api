<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddonMenuPrice extends Model
{
  protected $fillable = [
    'addon_menu_id', 'price', 'currency_id'
  ];

  /*
   * A addon menu price belongs to addon menu
   *
   *@
   */
  public function addon_menu()
  {
    return $this->belongsTo(AddonMenu::class);
  }
}
