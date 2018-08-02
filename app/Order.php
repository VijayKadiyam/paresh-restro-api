<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = [
    'description', 'source_id', 'total_amount'
  ];

  /*
   * To store a new order
   *
   *@
   */
  public function store()
  {
    $hotel = (new Hotel)->getHotel();
    $hotel->orders()->save($this);

    $this->storeTables(); 
    $this->storeContacts(); 

    return $this;
  }

  /*
   * To store tables
   *
   *@
   */
  public function storeTables()
  {
    if($tables = request()->tables) {
      foreach($tables as $table) {
        $no_of_customers = $table['no_of_customers'] ?? 0;
        $table = Table::where('id', '=', $table['id'])->first();
        $table->unsyncOrder($this);
        $table->syncOrder($this, $no_of_customers);
      }
    }

    return $this;
  }

  /*
   * To store tables
   *
   *@
   */
  public function storeContacts()
  {
    if($contacts = request()->contacts) {
      foreach($contacts as $contact) {
        $contact = Contact::where('id', '=', $contact['id'])->first();
        $contact->unsyncOrder($this);
        $contact->syncOrder($this);
      }
    }

    return $this;
  }

  /*
   * To reform a order data
   *
   *@
   */
  public function reform()
  {
    $this->update(request()->all()); 
    
    $this->storeTables();
    $this->storeContacts(); 

    return $this;
  }

  /*
   * To update order total amount
   *
   *@
   */
  public function addToTotalAmount($amount = 0)
  {
    $this->total_amount += $amount;
    $this->save();

    return $this;
  }
  public function removeFromTotalAmount($amount = 0)
  {
    $this->total_amount -= $amount;
    $this->save();

    return $this;
  }

  /*
   * A order belongs to a hotel
   *
   *@
   */
  public function hotel()
  {
    return $this->belongsTo(Hotel::class);
  }

  /*
   * An order belongs to a source
   *
   *@
   */
  public function source()
  {
    return $this->belongsTo(Source::class);
  } 

  /*
   * An order belongs to many tables
   *
   *@
   */
  public function tables()
  {
    return $this->belongsToMany(Table::class)
      ->withPivot('no_of_customers');
  }

  /*
   * A order has many tickets
   *
   *@
   */
  public function tickets()
  {
    return $this->hasMany(Ticket::class)
      ->with('order', 'recepie_menu', 'status');
  }

  /*
   * An order belongs to many contacts
   *
   *@
   */
  public function contacts()
  {
    return $this->belongsToMany(Contact::class);
  }
}
