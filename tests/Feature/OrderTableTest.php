<?php

namespace Tests\Feature;

use App\Order;
use App\Table;
use App\Source;
use App\Status;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTableTest extends TestCase
{
  use DatabaseTransactions;

  protected $order, $source, $table, $status;

  public function setUp()
  {
    parent::SetUp();

    $this->source = Source::find(1);

    $this->table = factory(Table::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->status = Status::find(1);

    $this->order = factory(Order::class)->create([
      'hotel_id'  =>  $this->hotel->id,
      'source_id' =>  $this->source->id
    ]);

    $this->table->syncOrder($this->order, 4);
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
            ],
            'tables'  =>  [
              0 =>  [
                'id'    =>  $this->table->id,
                'name'  =>  $this->table->name
              ]
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function order_saved_successfully_with_table()
  {
    $this->disableEH();
    $payload = [
      'description'  =>  'Order Description',
      'source_id'    =>  $this->source->id,
      'total_amount' =>  '5000',
      'tables'  =>  [
        0 =>  [
          'id'              =>  $this->table->id,
          'no_of_customers' =>  '4'
        ]
      ]
    ];

    $this->json('post', '/api/orders', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'description'  =>  'Order Description',
          'source_id'    =>  $this->source->id,
          'total_amount' =>  '5000',
          'source'  =>  [
            'id'      =>  $this->source->id,
            'source'  =>  $this->source->source
          ],
          'tables'  =>  [
            0 =>  [
              'id'    =>  $this->table->id,
              'name'  =>  $this->table->name,
              'pivot' =>  [
                'no_of_customers' =>  4
              ]
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_order_fetched_successfully_with_table()
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
          ],
          'tables'  =>  [
            0 =>  [
              'id'    =>  $this->table->id,
              'name'  =>  $this->table->name,
              'pivot' =>  [
                'no_of_customers' =>  4
              ]
            ]
          ]
        ]
      ]);  
  }

  /** @test */
  function order_updated_successfully_with_table()
  {
    $payload = [
      'id'          =>  $this->order->id,
      'hotel_id'    =>  $this->order->hotel_id,
      'description' =>  'Description changed',
      'source_id'   =>  $this->order->source_id,
      'total_amount'=>  '5000',
      'tables'  =>  [
        0 =>  [
          'id'              =>  $this->table->id,
          'no_of_customers' =>  '5'
        ]
      ]
    ];

    $this->json('patch', '/api/orders/' . $this->order->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'description'  =>  'Description changed',
          'source_id'    =>  $this->source->id,
          'total_amount' =>  '5000',
          'source'  =>  [
            'id'      =>  $this->source->id,
            'source'  =>  $this->source->source
          ],
          'tables'  =>  [
            0 =>  [
              'id'              =>  $this->table->id,
              'pivot' =>  [
                'no_of_customers' =>  '5'
              ]
            ]
          ]
        ]
      ]);
  }

}
