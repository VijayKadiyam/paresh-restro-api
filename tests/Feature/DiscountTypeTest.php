<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\DiscountType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DiscountTypeTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function discount_types_fetched_successfully()
  {
    factory(DiscountType::class)->create([
      'type'  =>  'By Percent'
    ]);

    $this->json('get', '/api/discount-types')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'type'  =>  'By Percent'
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_type()
  {
    $this->json('post', '/api/discount-types')
      ->assertStatus(422)
      ->assertExactJson([
        'errors' => [
          "type"    =>  ["The type field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function discount_type_saved_successfully()
  { 
    $payload = [
      'type' => 'By percent'
    ];

    $this->json('post', '/api/discount-types', $payload)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'type'  =>  'By percent'
        ]
      ]);
  }

  /** @test */
  function single_discount_type_fetched_successfully()
  {
    $type = factory(DiscountType::class)->create([
      'type'  =>  'By percent'
    ]);

    $this->json('get', "/api/discount-types/$type->id")
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'type'  =>  'By percent'
        ]
      ]);
  }

  /** @test */
  function discount_type_updated_successfully()
  {
    $type = factory(DiscountType::class)->create([
      'type'  =>  'By percent'
    ]);
    $type->type = "By Amount";

    $this->json('patch', "/api/discount-types/$type->id", $type->toArray())
      ->assertStatus(200)
      ->assertJson([
        'data'  => [
          'type'  =>  'By Amount'
        ]
      ]);
  }
}
