<?php

namespace App;

trait AddressTrait
{
  /*
   * To store addresses against a model
   *
   *@
   */
  public function storeAddresses($hotel, $addresses)
  {
    $hotel = $hotel instanceof Hotel ? $hotel : (Hotel::find($hotel));
    if($addresses) {
      foreach($addresses as $address) {
        Address::create([
          'hotel_id'      =>  $hotel->id,
          'subject_id'    =>  $this->id,
          'subject_type'  =>  get_class($this),
          'address'       =>  $address['address'] ? $address['address'] : '',
          'state'         =>  $address['state'] ? $address['state'] : '',
          'state_code'    =>  $address['state_code'] ? $address['state_code'] : '',
          'pincode'       =>  $address['pincode'] ? $address['pincode'] : '',
        ]);
      }
    } 
  }

  /*
   * To update addresses against a model
   *
   *@
   */
  public function updateAddresses($hotel, $address)
  {
    if($address) {
      foreach($address as $address) {
        optional(Address::where('id', '=', $address['id'])->first())->update($address);
      }
    }
  }
}