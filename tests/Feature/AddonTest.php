<?php

namespace Tests\Feature;

use App\Addon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddonTest extends TestCase
{
  use DatabaseTransactions;

  protected $addon;

  public function setUp()
  {
    parent::SetUp();

    $this->addon = factory(Addon::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/addons')
      ->assertStatus(401); 
  }

  /** @test */
  function addons_fetched_successfully()
  {
    $this->json('get', '/api/addons', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'id'          =>  $this->addon->id,
            'hotel_id'    =>  $this->addon->hotel_id,
            'name'        =>  $this->addon->name,
            'description' =>  $this->addon->description
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_name_and_description()
  {
    $this->json('post', '/api/addons', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "description" =>  ["The description field is required."],
          "name"        =>  ["The name field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function addon_saved_successfully()
  {
    $payload = [
      'name'        =>  'Dal Fry',
      'description' =>  'Tasty Food'
    ];

    $this->json('post', '/api/addons', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'        =>  'Dal Fry',
          'description' =>  'Tasty Food'
        ]
      ]);
  }

  /** @test */
  function single_addon_fetched_successfully()
  {
    $this->json('get', '/api/addons/' . $this->addon->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'hotel_id'    =>  $this->addon->hotel_id,
          'name'        =>  $this->addon->name,
          'description' =>  $this->addon->description
        ]
      ]);  
  }

  /** @test */
  function it_requires_name_and_description_while_updtaing()
  {
    $this->json('patch', '/api/addons/' . $this->addon->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "description" =>  ["The description field is required."],
          "name"        =>  ["The name field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }
}
