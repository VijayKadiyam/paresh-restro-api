<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  protected $fillable = [
    'role'
  ];

  /*
   * A role belongs to many users
   *
   *@
   */
  public function users()
  {
    return $this->belongsToMany(User::class)
      ->withTimeStamps();
  }
}
