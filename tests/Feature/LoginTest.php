<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
  use DatabaseTransactions; 

  /** @test */
  function it_requires_phone_and_password()
  {
    $this->json('post', '/api/login')
      ->assertStatus(422)
      ->assertExactJson([
        'errors'  =>  [
          "phone"     =>  ["The phone field is required."],
          "password"  =>  ["The password field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function user_logged_in_successfully()
  {
    $this->user->assignRole('admin');

    $payload = [
      'phone'     =>  $this->user->phone,
      'password'  =>  '123456'
    ];

    $this->json('post', '/api/login', $payload)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'phone'     =>  $this->user->phone 
        ]
      ]);
  }
}
