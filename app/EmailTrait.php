<?php

namespace App;

trait EmailTrait
{
  /*
   * To store emails against a model
   *
   *@
   */
  public function storeEmails($hotel, $emails)
  {
    $hotel = $hotel instanceof Hotel ? $hotel : (Hotel::find($hotel));
    if($emails) {
      foreach($emails as $email) {
        if(isset($email['email']))
          Email::create([
            'hotel_id'      =>  $hotel->id,
            'subject_id'    =>  $this->id,
            'subject_type'  =>  get_class($this),
            'email'         =>  $email['email']
          ]);
      }
    } 
  }

  /*
   * To update emails against a model
   *
   *@
   */
  public function updateEmails($hotel, $emails)
  {
    if($emails) {
      foreach($emails as $email) {
        if(isset($email['id'])) {
          $emailModel = Email::where('id', '=', $email['id'])->first();
          if(isset($email['email']))
            optional($emailModel)->update($email);
          else
            optional($emailModel)->delete();
        }
        else if(isset($email['email']))
          Email::create([
            'hotel_id'      =>  $hotel->id,
            'subject_id'    =>  $this->id,
            'subject_type'  =>  get_class($this),
            'email'         =>  $email['email']
          ]);
      }
    }
  }
}