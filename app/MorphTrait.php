<?php

namespace App;

trait MorphTrait
{ 
    /*
   * A model has many phones
   *
   *@ subject is the relation with the Phone model
   */
  public function phones()
  {
    return $this->morphMany(Phone::class, 'subject');
  }

  /*
   * A model has many emails
   *
   *@ subject is the relation with the Email model
   */
  public function emails()
  {
    return $this->morphMany(Email::class, 'subject');
  } 

  /*
   * A model has many addresses
   *
   *@ subject is the relation with the Address model
   */
  public function addresses()
  {
    return $this->morphMany(Address::class, 'subject');
  }  

  /*
   * A model has many accounts
   *
   *@ subject is the relation with the account model
   */
  public function accounts()
  {
    return $this->morphMany(Account::class, 'subject');
  }

  /*
   * A model has many images
   *
   *@ subject is the relation with the image model
   */
  public function images()
  {
    return $this->morphMany(Image::class, 'subject');
  }
}