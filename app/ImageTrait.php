<?php

namespace App;

trait ImageTrait
{
  /*
   * To store images against a model
   *
   *@
   */
  public function storeImages($hotel, $images)
  {
    $hotel = $hotel instanceof Hotel ? $hotel : (Hotel::find($hotel));
    if($images) {
      foreach($images as $image) {
        Image::create([
          'hotel_id'      =>  $hotel->id,
          'subject_id'    =>  $this->id,
          'subject_type'  =>  get_class($this),
          'image_path'    =>  $image['image_path']
        ]);
      }
    } 
  }

  /*
   * To update images against a model
   *
   *@
   */
  public function updateImages($hotel, $images)
  {
    if($images) {
      foreach($images as $image) {
        optional(Image::where('id', '=', $image['id'])->first())->update($image); 
      }
    }
  }
}