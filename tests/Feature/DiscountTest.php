<?php

namespace Tests\Feature;

use App\Discount;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DiscountTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function discounts_fetched_successfully()
  {
    factory(Discount::class)->create([
      'name'    =>  '5 percent',
      'percent' =>  '5'
    ]);

    $this->json('get', '/api/discounts')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'name'    =>  '5 percent',
            'percent' =>  '5'
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_name_and_percent()
  {
    $this->json('post', '/api/discounts')
      ->assertStatus(422)
      ->assertExactJson([
        'errors' => [
          "name"    =>  ["The name field is required."],
          "percent"    =>  ["The percent field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function discount_saved_successfully()
  {
    $payload = [
      'name'    =>  '5 percent',
      'percent' =>  '5'
    ];

    $this->json('post', '/api/discounts', $payload)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'    =>  '5 percent',
          'percent' =>  '5'
        ]
      ]);
  }

  /** @test */
  function single_discount_fetched_successfully()
  {
    $discount = factory(Discount::class)->create([
      'name'    =>  '5 Percent',
      'percent' =>  '5'
    ]);

    $this->json('get', "/api/discounts/$discount->id")
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'    =>  '5 Percent',
          'percent' =>  '5'
        ]
      ]);
  }

  /** @test */
  function role_updated_successfully()
  {
    $discount = factory(Discount::class)->create([
      'name'    =>  '5 Percent',
      'percent' =>  '5'
    ]);
    $discount->name = "6 Percent";
    $discount->percent = "6";

    $this->json('patch', "/api/discounts/$discount->id", $discount->toArray())
      ->assertStatus(200)
      ->assertJson([
        'data'  => [
          'name'    =>  '6 Percent',
          'percent' =>  '6'
        ]
      ]);
  }
}
