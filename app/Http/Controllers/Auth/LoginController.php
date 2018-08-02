<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/home';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  } 

  /**
   * Get the login username to be used by the controller.
   *
   * @return string
   */
  public function username()
  {
    return 'phone';
  }

  /*
   * To custom login a user
   *
   *@
   */
  public function login(Request $request)
  {
    $this->validateLogin($request);
    
    if($this->attemptLogin($request))
    {
      $user = $this->guard()->user();
      $user->generateToken();

      $user->role = $user->roles[0]->role;
      $user->role_id = $user->roles[0]->id;

      return response()->json([
        'data'  =>  $user->toArray()
      ]);
    }

    return $this->sendFailedLoginResponse($request);
  }
}
