<?php

namespace Tests\Feature;

use App\Order;
use App\Source;
use App\Status;
use App\Ticket;
use App\Recepie;
use App\MenuType;
use Tests\TestCase;
use App\RecepieMenu;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketTest extends TestCase
{
  use DatabaseTransactions;

  protected $order, $source, $recepieMenu, $recepie, $type, $status, $ticket;

  public function setUp()
  {
    parent::SetUp();

    $this->source = Source::find(1);

    $this->order = factory(Order::class)->create([
      'hotel_id'  =>  $this->hotel->id,
      'source_id' =>  $this->source->id
    ]);

    $this->recepie = factory(Recepie::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->type = MenuType::find(1);

    $this->recepieMenu = factory(RecepieMenu::class)->create([
      'hotel_id'    =>  $this->hotel->id,
      'recepie_id'  =>  $this->recepie->id,
      'type_id'     =>  $this->type->id
    ]);

    $this->status = Status::find(1); 

    $this->ticket = factory(Ticket::class)->create([
      'order_id'        =>  $this->order->id,
      'recepie_menu_id' =>  $this->recepieMenu->id,
      'status_id'       =>  $this->status->id
    ]);
    $this->order->addToTotalAmount($this->ticket->amount);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/orders/' . $this->order->id . '/tickets')
      ->assertStatus(401); 
  }

  /** @test */
  function tickets_fetched_successfully()
  {
    $this->disableEH();
    $this->json('get', '/api/orders/' . $this->order->id . '/tickets', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'id'              =>  $this->ticket->id,
            'order_id'        =>  $this->ticket->order_id,
            'recepie_menu_id' =>  $this->ticket->recepie_menu_id,
            'qty'             =>  $this->ticket->qty,
            'description'     =>  $this->ticket->description,
            'status_id'       =>  $this->ticket->status_id,
            'amount'          =>  $this->ticket->amount,
            'order' =>  [
              'id'            =>  $this->order->id,
              'total_amount'  =>  '1000'
            ],
            'recepie_menu'  =>  [
              'id'  =>  $this->recepieMenu->id
            ],
            'status'  =>  [
              'id'  =>  $this->status->id
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_recepieMenuId_qty_statusId_amount()
  {
    $this->json('post', '/api/orders/' . $this->order->id . '/tickets', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "recepie_menu_id"      =>  ["The recepie menu id field is required."],
          "qty"                  =>  ["The qty field is required."],
          "status_id"            =>  ["The status id field is required."],
          "amount"               =>  ["The amount field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function ticket_saved_successfully()
  {
    $payload = [
      'order_id'        =>  $this->order->id,
      'recepie_menu_id' =>  $this->recepieMenu->id,
      'qty'             =>  '5',
      'description'     =>  'description',
      'status_id'       =>  $this->status->id,
      'amount'          =>  '1000'
    ];

    $this->json('post', '/api/orders/' . $this->order->id . '/tickets', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'order_id'        =>  $this->order->id,
          'recepie_menu_id' =>  $this->recepieMenu->id,
          'qty'             =>  '5',
          'description'     =>  'description',
          'status_id'       =>  $this->status->id,
          'amount'          =>  '1000',
          'order' =>  [
            'id'            =>  $this->order->id,
            'total_amount'  =>  '2000'
          ],
          'recepie_menu'  =>  [
            'id'  =>  $this->recepieMenu->id
          ],
          'status'  =>  [
            'id'  =>  $this->status->id
          ]
        ]
      ]);
  }

  /** @test */
  function single_ticket_fetched_successfully()
  {
    $this->json('get', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'              =>  $this->ticket->id,
          'order_id'        =>  $this->ticket->order_id,
          'recepie_menu_id' =>  $this->ticket->recepie_menu_id,
          'qty'             =>  $this->ticket->qty,
          'description'     =>  $this->ticket->description,
          'status_id'       =>  $this->ticket->status_id,
          'amount'          =>  $this->ticket->amount,
          'order' =>  [
            'id'            =>  $this->order->id,
            'total_amount'  =>  '1000'
          ],
          'recepie_menu'  =>  [
            'id'  =>  $this->recepieMenu->id
          ],
          'status'  =>  [
            'id'  =>  $this->status->id
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_recepieMenuId_qty_statusId_amount_while_updating()
  {
    $this->json('patch', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "recepie_menu_id"      =>  ["The recepie menu id field is required."],
          "qty"                  =>  ["The qty field is required."],
          "status_id"            =>  ["The status id field is required."],
          "amount"               =>  ["The amount field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function ticket_updated_successfully()
  {
    $payload = [
      'id'              =>  $this->ticket->id,
      'order_id'        =>  $this->ticket->order_id,
      'recepie_menu_id' =>  $this->ticket->recepie_menu_id,
      'qty'             =>  '5',
      'description'     =>  $this->ticket->description,
      'status_id'       =>  $this->ticket->status_id,
      'amount'          =>  '2000',
    ];

    $this->json('patch', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'order_id'        =>  $this->order->id,
          'recepie_menu_id' =>  $this->recepieMenu->id,
          'qty'             =>  '5',
          'description'     =>  'description',
          'status_id'       =>  $this->status->id,
          'amount'          =>  '2000',
          'order' =>  [
            'id'            =>  $this->order->id,
            'total_amount'  =>  '2000'
          ],
          'recepie_menu'  =>  [
            'id'  =>  $this->recepieMenu->id
          ],
          'status'  =>  [
            'id'  =>  $this->status->id
          ]
        ]
      ]);
  }

  /** @test */
  function ticket_deleted_successfully()
  {
    $this->json('delete', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id, [], $this->headers)
      ->assertStatus(200);

    $this->assertCount(0, $this->order->tickets);
  }
}
