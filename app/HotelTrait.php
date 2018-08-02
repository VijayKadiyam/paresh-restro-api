<?php

namespace App;

trait HotelTrait
{
  /*
   * To get the current hotel
   *
   *@
   */
  public function getHotel()
  {
    return Hotel::where('id' , '=', request()->header('hotel-id'))->first();
  }
}