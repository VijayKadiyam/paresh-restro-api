<?php

namespace App;

use App\MorphTrait;
use App\PhoneTrait;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  use PhoneTrait, EmailTrait, AddressTrait, AccountTrait, ImageTrait, MorphTrait;

  protected $fillable = [
    'hotel_id', 'company_name', 'name', 'pan_no', 'gstn_no'
  ];

  /*
   * To store a new contact
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->contacts()->save($this);
    $this->storePhones($hotel, request()->phones);
    $this->storeEmails($hotel, request()->emails);
    $this->storeAddresses($hotel, request()->addresses);
    $this->storeAccounts($hotel, request()->accounts);
    $this->storeImages($hotel, request()->images);
    // Store a contact type
    $this->storeTypes(request()->types);

    return $this;
  }

  /*
   * To reform a contact data
   *
   *@
   */
  public function reform()
  {
    $hotel = (new Hotel)->getHotel();
    $this->update(request()->all());
    $this->updatePhones($hotel, request()->phones);
    $this->updateEmails($hotel, request()->emails);
    $this->updateAddresses($hotel, request()->addresses);
    $this->updateAccounts($hotel, request()->accounts);
    $this->updateImages($hotel, request()->images);
    // Store a contact type
    $this->storeTypes(request()->types);

    return $this;
  }

  /*
   * To assign a contact with a type
   *
   *@
   */
  public function storeTypes($types)
  {
    $this->types()->sync($types);
  }

  /*
   * A contact belongs to a hotel
   *
   *@
   */
  public function hotel()
  {
    return $this->belongsTo(Hotel::class);
  } 

  /*
   * A contact belongs to many types
   *
   *@
   */
  public function types()
  {
    return $this->belongsToMany(ContactType::class, 'contact_type', 'contact_id', 'contact_type_id');
  }

  /*
   * A contact belongs to many orders
   *
   *@
   */
  public function orders()
  {
    return $this->belongsToMany(Order::class);
  }

  /*
   * If ordered through table then sync contact_id and order_id
   *
   *@
   */
  public function syncOrder($order, $no_of_customers = 0)
  {
    $this->orders()->attach($order);

    return $this;
  }

  /*
   * TO unsync an order with a contact
   *
   *@
   */
  public function unsyncOrder($order)
  {
    $this->orders()->detach($order);

    return $this;
  }
}
