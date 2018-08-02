<?php

namespace Tests;

use App\User;
use App\Hotel;
use Exception;
use App\Exceptions\Handler;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{

  protected $user, $hotel, $headers;

  /**
   * Creates the application.
   *
   * @return \Illuminate\Foundation\Application
   */
  public function createApplication()
  {
      $app = require __DIR__.'/../bootstrap/app.php';

      $app->make(Kernel::class)->bootstrap(); 

      $this->user = factory(User::class)->create([ 
        'password'  =>  bcrypt('123456')
      ]);
      $this->user->generateToken();

      $this->hotel = factory(Hotel::class)->create();
      $this->hotel->assignUser($this->user);
      $this->hotel->storePhones($this->hotel, [ 
        0 => [
          'phone' =>  '9579862371'
        ]
      ]);

      $this->hotel->storeEmails($this->hotel, [ 
        0 => [
          'email' =>  'email@email.com'
        ]
      ]);

      $this->hotel->storeAddresses($this->hotel, [ 
        0 => [
          'address' =>  'address',
          'state'   =>  'state',
          'state_code' =>  'state_code',
          'pincode' =>  'pincode',
        ]
      ]);

      $this->hotel->storeAccounts($this->hotel, [ 
        0 => [
          'acc_no'    =>  'acc_no',
          'acc_name'  =>  'acc_name',
          'ifsc_code' =>  'ifsc_code',
          'branch'    =>  'branch',
        ]
      ]);

      $this->hotel->storeImages($this->hotel, [ 
        0 => [
          'image_path' =>  'image_path'
        ]
      ]);

      $this->headers = [
        'Authorization' =>  'Bearer ' . $this->user->api_token,
        'hotel-id'      =>  $this->hotel->id
      ];

      return $app;
  }

  /*
   * To disable the exception handling
   *
   *@
   */
  public function disableEH()
  {
    app()->instance(Handler::class, new class extends Exception {
      public function __construct(){}
      public function report(Exception $exception){}
      public function render($request, Exception $exception)
      {
        throw $exception;
      }
    });
  }
}
