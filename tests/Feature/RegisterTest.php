<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase
{
  use DatabaseTransactions;

  public function setUp()
  {
    parent::setUp();

    factory(\App\Role::class)->create([
      'role'  =>  'admin',
    ]);

    factory(\App\Role::class)->create([
      'role'  =>  'user',
    ]);
  }

  /** @test */
  function it_requires_phone_and_password()
  {
    $this->json('POST', 'api/register')
      ->assertStatus(422)
      ->assertExactJson([
        'errors'  =>  [
          'name'      =>  ["The name field is required."],
          'phone'     =>  ["The phone field is required."],
          'password'  =>  ["The password field is required."]
        ],
        'message' =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function it_required_password_confirmation()
  {
    $payload = [
      'name'      =>  'test',
      'phone'     =>  '9579862372',
      'password'  =>  '123456'
    ];

    $this->json('POST', 'api/register', $payload)
      ->assertStatus(422)
      ->assertExactJson([
        'errors'  =>  [ 
          'password'  =>  ["The password confirmation does not match."]
        ],
        'message' =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function user_is_registered_successfully()
  {
    $this->disableEH();
    $payload = [
      'name'                  =>  'test',
      'phone'                 =>  '9579862372',
      'password'              =>  '123456',
      'password_confirmation' =>  '123456'
    ];

    $this->json('POST', 'api/register', $payload)
      ->assertStatus(200)
      ->assertJsonStructure([
        'data'  => [
         'name',
         'phone',
         'api_token'
        ]
      ]);

    $user = User::where('phone', '=', '9579862372')->first();

    $this->assertEquals(true, $user->hasRole('admin'));
  }

  /** @test */
  function user_is_assigned_a_role()
  { 
    $token = $this->user->generateToken();

    $header = [
      'Authorization' =>  "Bearer $token"
    ];

    $this->user->assignRole('admin');

    $this->user->hasRole('admin');

    $this->assertEquals(true, $this->user->hasRole('admin'));
  }
}
