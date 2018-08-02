<?php

namespace App;

use App\RoleTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use RoleTrait, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password', 'phone'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /*
   * To generate the token
   *
   *@
   */
  public function generateToken()
  {
    $this->api_token = str_random(60);
    $this->save();

    return $this->api_token;
  }

  /*
   * A user belongs to many hotels 
   *
   *@
   */
  public function hotels()
  {
    return $this->belongsToMany(Hotel::class)
      ->with('phones', 'emails', 'addresses', 'accounts', 'images')
      ->withTimeStamps();
  }
}
