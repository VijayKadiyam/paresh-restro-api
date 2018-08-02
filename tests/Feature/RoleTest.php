<?php

namespace Tests\Feature;

use App\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function roles_fetched_successfully()
  {
    factory(Role::class)->create([
      'role'  =>  'admin'
    ]);

    $this->json('get', '/api/roles')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'role'  =>  'admin'
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_role()
  {
    $this->json('post', '/api/roles')
      ->assertStatus(422)
      ->assertExactJson([
        'errors' => [
          "role"    =>  ["The role field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function role_saved_successfully()
  { 
    $payload = [
      'role' => 'admin'
    ];

    $this->json('post', '/api/roles', $payload)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'admin'
        ]
      ]);
  }

  /** @test */
  function single_role_fetched_successfully()
  {
    $role = factory(Role::class)->create([
      'role'  =>  'admin'
    ]);

    $this->json('get', "/api/roles/$role->id")
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'role'  =>  'admin'
        ]
      ]);
  }

  /** @test */
  function role_updated_successfully()
  {
    $role = factory(Role::class)->create([
      'role'  =>  'admin'
    ]);
    $role->role = "user";

    $this->json('patch', "/api/roles/$role->id", $role->toArray())
      ->assertStatus(200)
      ->assertJson([
        'data'  => [
          'role'  =>  'user'
        ]
      ]);
  }
}
