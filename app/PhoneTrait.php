<?php

namespace App;

trait PhoneTrait
{
  /*
   * To store phones against a model
   *
   *@
   */
  public function storePhones($hotel, $phones)
  {
    $hotel = $hotel instanceof Hotel ? $hotel : (Hotel::find($hotel));
    if($phones) {
      foreach($phones as $phone) {
        if(isset($phone['phone']))
          Phone::create([
            'hotel_id'      =>  $hotel->id,
            'subject_id'    =>  $this->id,
            'subject_type'  =>  get_class($this),
            'phone'         =>  $phone['phone']
          ]); 
      }
    } 
  }

  /*
   * To update phones against a model
   *
   *@
   */
  public function updatePhones($hotel, $phones)
  {
    $hotel = $hotel instanceof Hotel ? $hotel : (Hotel::find($hotel));
    if($phones) {
      foreach($phones as $phone) {
        if(isset($phone['id'])) {
          $phoneModel = Phone::where('id', '=', $phone['id'])->first();
          if(isset($phone['phone']))
            optional($phoneModel)->update($phone);
          else
            optional($phoneModel)->delete();
        }
        else if(isset($phone['phone']))
          Phone::create([
            'hotel_id'      =>  $hotel->id,
            'subject_id'    =>  $this->id,
            'subject_type'  =>  get_class($this),
            'phone'         =>  $phone['phone']
          ]);
      }
    }
  } 
}