<?php

namespace App;

use App\Hotel;
use App\AddonMenu;
use App\EmailTrait;
use App\HotelTrait;
use App\ImageTrait;
use App\MorphTrait;
use App\PhoneTrait;
use App\AccountTrait;
use App\AddressTrait;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
  use HotelTrait, PhoneTrait, EmailTrait, AddressTrait, AccountTrait, ImageTrait, MorphTrait;

  protected $fillable = [
    'name', 'pan_no', 'gstn_no'
  ];

  /*
   * To store a new hotel
   *
   *@
   */
  public function store($user)
  {
    $this->save();
    $this->assignUser($user);
    $this->storePhones($this, request()->phones);
    $this->storeEmails($this, request()->emails);
    $this->storeAddresses($this, request()->addresses);
    $this->storeAccounts($this, request()->accounts);
    $this->storeImages($this, request()->images);
    
    return $this;
  }

  /*
   * To reform a hotel data
   *
   *@
   */
  public function reform()
  {
    $this->update(request()->all());
    $this->updatePhones($this, request()->phones);
    $this->updateEmails($this, request()->emails);
    $this->updateAddresses($this, request()->addresses);
    $this->updateAccounts($this, request()->accounts);
    $this->updateImages($this, request()->images);

    return $this;
  }

  /*
   * A hotel belongs to many users
   *
   *@
   */
  public function users()
  {
    return $this->belongsToMany(User::class)
      ->withTimeStamps();
  }

  /*
   * To assign a user against a hotel
   *
   *@
   */
  public function assignUser($user)
  {
    $user->hotels()->find($this->id) ?  '' : $user->hotels()->syncWithoutDetaching($this); 

    return $this; 
  }

  /*
   * A hotel has many contacts
   *
   *@
   */
  public function contacts()
  {
    return $this->hasMany(Contact::class)
      ->with('phones', 'emails', 'addresses', 'accounts', 'images', 'types');
  }

  /*
   * A hotel has many recepies
   *
   *@
   */
  public function recepies()
  {
    return $this->hasMany(Recepie::class)
      ->with('images');
  }

  /*
   * A hotel has many addons
   *
   *@
   */
  public function addons()
  {
    return $this->hasMany(Addon::class);
  }

  /*
   * A hotel has many recepie menus
   *
   *@
   */
  public function recepie_menus()
  {
    return $this->hasMany(RecepieMenu::class)
      ->with('prices', 'recepie', 'type')
      ->orderBy('type_id', 'ASC');
  }

  /*
   * A hotel has many addon menus
   *
   *@
   */
  public function addon_menus()
  {
    return $this->hasMany(AddonMenu::class)
      ->with('prices');
  }

  /*
   * A hotel has many tables
   *
   *@
   */
  public function tables()
  {
    return $this->hasMany(Table::class)
      ->with('status', 'images', 'orders');
  }

  /*
   * A hotel has many orders
   *
   *@
   */
  public function orders()
  {
    return $this->hasMany(Order::class)
      ->with('source', 'tables', 'contacts', 'tickets');
  }
}
