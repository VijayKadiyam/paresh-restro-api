<?php

namespace Tests\Feature;

use App\Order;
use App\Source;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
  use DatabaseTransactions;

  protected $order, $source;

  public function setUp()
  {
    parent::SetUp();

    $this->source = Source::find(1);

    $this->order = factory(Order::class)->create([
      'hotel_id'  =>  $this->hotel->id,
      'source_id' =>  $this->source->id
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/orders')
      ->assertStatus(401); 
  }

  /** @test */
  function orders_fetched_successfully()
  {
    $this->json('get', '/api/orders', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'id'          =>  $this->order->id,
            'hotel_id'    =>  $this->order->hotel_id,
            'description' =>  $this->order->description,
            'source_id'   =>  $this->order->source_id,
            'description' =>  $this->order->description,
            'source'  =>  [
              'id'      =>  $this->source->id,
              'source'  =>  $this->source->source
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_description_sourceId_and_totalAmount()
  {
    $this->json('post', '/api/orders', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "source_id"        =>  ["The source id field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function order_saved_successfully()
  {
    $payload = [
      'description'  =>  'Order Description',
      'source_id'    =>  $this->source->id,
    ];

    $this->json('post', '/api/orders', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'description'  =>  'Order Description',
          'source_id'    =>  $this->source->id,
          'source'  =>  [
            'id'      =>  $this->source->id,
            'source'  =>  $this->source->source
          ]
        ]
      ]);
  }

  /** @test */
  function single_order_fetched_successfully()
  {
    $this->json('get', '/api/orders/' . $this->order->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'          =>  $this->order->id,
          'hotel_id'    =>  $this->order->hotel_id,
          'description' =>  $this->order->description,
          'source_id'   =>  $this->order->source_id,
          'description' =>  $this->order->description,
          'source'  =>  [
            'id'      =>  $this->source->id,
            'source'  =>  $this->source->source
          ]
        ]
      ]);  
  }

  /** @test */
  function it_requires_description_sourceId_and_totalAmount_while_updating()
  {
    $this->json('patch', '/api/orders/' . $this->order->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "source_id"        =>  ["The source id field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function order_updated_successfully()
  {
    $payload = [
      'id'          =>  $this->order->id,
      'hotel_id'    =>  $this->order->hotel_id,
      'description' =>  'Description changed',
      'source_id'   =>  $this->order->source_id,
    ];

    $this->json('patch', '/api/orders/' . $this->order->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'description'  =>  'Description changed',
          'source_id'    =>  $this->source->id,
          'source'  =>  [
            'id'      =>  $this->source->id,
            'source'  =>  $this->source->source
          ]
        ]
      ]);
  }

  /** @test */
  function orders_fetched_between_dates()
  {
    $this->disableEH(); 
    $this->json('get', '/api/orders?fromDate=2018-07-31&toDate=2018-08-03', [], $this->headers)
      ->assertStatus(200); 
  }
}
