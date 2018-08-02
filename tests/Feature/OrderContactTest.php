<?php

namespace Tests\Feature;

use App\Order;
use App\Source;
use App\Status;
use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderContactTest extends TestCase
{
  use DatabaseTransactions;

  protected $order, $source, $contact, $status;

  public function setUp()
  {
    parent::SetUp();

    $this->source = Source::find(1);

    $this->contact = factory(Contact::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->status = Status::find(1);

    $this->order = factory(Order::class)->create([
      'hotel_id'  =>  $this->hotel->id,
      'source_id' =>  $this->source->id
    ]);

    $this->contact->syncOrder($this->order, 4);
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
            'contacts'  =>  [
              0 =>  [
                'id'    =>  $this->contact->id,
                'name'  =>  $this->contact->name
              ]
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function order_saved_successfully_with_contacts()
  {
    $this->disableEH();
    $payload = [
      'description'  =>  'Order Description',
      'source_id'    =>  $this->source->id,
      'total_amount' =>  '5000',
      'contacts'  =>  [
        0 =>  [
          'id'              =>  $this->contact->id 
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
          'contacts'  =>  [
            0 =>  [
              'id'    =>  $this->contact->id,
              'name'  =>  $this->contact->name 
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_order_fetched_successfully_with_contacts()
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
          'contacts'  =>  [
            0 =>  [
              'id'    =>  $this->contact->id,
              'name'  =>  $this->contact->name 
            ]
          ]
        ]
      ]);  
  }

  /** @test */
  function order_updated_successfully_with_contacts()
  {
    $payload = [
      'id'          =>  $this->order->id,
      'hotel_id'    =>  $this->order->hotel_id,
      'description' =>  'Description changed',
      'source_id'   =>  $this->order->source_id,
      'total_amount'=>  '5000',
      'contacts'  =>  [
        0 =>  [
          'id'              =>  $this->contact->id 
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
          'contacts'  =>  [
            0 =>  [
              'id'              =>  $this->contact->id 
            ]
          ]
        ]
      ]);
  }

}
