<?php

namespace Tests\Feature;

use App\Addon;
use App\Order;
use App\Source;
use App\Status;
use App\Ticket;
use App\Recepie;
use App\MenuType;
use App\AddonMenu;
use Tests\TestCase;
use App\RecepieMenu;
use App\TicketAddon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketAddonTest extends TestCase
{
  use DatabaseTransactions;

  protected $order, $source, $recepieMenu, $recepie, $type, $status, $ticket, $ticketAddon, $addon, $addonMenu;

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

    $this->addon = factory(Addon::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->addonMenu = factory(AddonMenu::class)->create([
      'hotel_id'    =>  $this->hotel->id,
      'addon_id'  =>  $this->addon->id
    ]);

    $this->ticketAddon = factory(TicketAddon::class)->create([
      'ticket_id'       =>  $this->ticket->id,
      'addon_menu_id'   =>  $this->addonMenu->id,
      'status_id'       =>  $this->status->id
    ]);
    $this->order->addToTotalAmount($this->ticketAddon->amount);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons')
      ->assertStatus(401); 
  }

  /** @test */
  function ticket_addons_fetched_successfully()
  {
    $this->disableEH();
    $this->json('get', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'id'              =>  $this->ticketAddon->id,
            'ticket_id'       =>  $this->ticketAddon->ticket_id,
            'addon_menu_id'   =>  $this->ticketAddon->addon_menu_id,
            'qty'             =>  $this->ticketAddon->qty,
            'description'     =>  $this->ticketAddon->description,
            'status_id'       =>  $this->ticketAddon->status_id,
            'amount'          =>  $this->ticketAddon->amount,
            'ticket'          =>  [
              'id'  =>  $this->ticket->id,
              'order' =>  [
                'id'            =>  $this->order->id,
                'total_amount'  =>  '2000'
              ],
            ], 
            'addon_menu'  =>  [
              'id'  =>  $this->addonMenu->id
            ],
            'status'  =>  [
              'id'  =>  $this->status->id
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_addonMenuId_qty_statusId_amount()
  {
    $this->json('post', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "addon_menu_id"        =>  ["The addon menu id field is required."],
          "qty"                  =>  ["The qty field is required."],
          "status_id"            =>  ["The status id field is required."],
          "amount"               =>  ["The amount field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function ticket_addon_saved_successfully()
  {
    $this->disableEH();
    $payload = [
      'ticket_id'       =>  $this->ticket->id,
      'addon_menu_id'   =>  $this->addonMenu->id,
      'qty'             =>  '5',
      'description'     =>  'description',
      'status_id'       =>  $this->status->id,
      'amount'          =>  '1000'
    ];

    $this->json('post', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'ticket_id'       =>  $this->ticket->id,
          'addon_menu_id'   =>  $this->addonMenu->id,
          'qty'             =>  '5',
          'description'     =>  'description',
          'status_id'       =>  $this->status->id,
          'amount'          =>  '1000',
          'ticket'          =>  [
            'id'  =>  $this->ticket->id,
            'order' =>  [
              'id'            =>  $this->order->id,
              'total_amount'  =>  '3000'
            ],
          ], 
          'addon_menu'  =>  [
            'id'  =>  $this->addonMenu->id
          ],
          'status'  =>  [
            'id'  =>  $this->status->id
          ]
        ] 
      ]);
  }

  /** @test */
  function single_ticket_addon_fetched_successfully()
  {
    $this->json('get', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons/' . $this->ticketAddon->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'              =>  $this->ticketAddon->id,
          'ticket_id'       =>  $this->ticketAddon->ticket_id,
          'addon_menu_id'   =>  $this->ticketAddon->addon_menu_id,
          'qty'             =>  $this->ticketAddon->qty,
          'description'     =>  $this->ticketAddon->description,
          'status_id'       =>  $this->ticketAddon->status_id,
          'amount'          =>  $this->ticketAddon->amount,
          'ticket'          =>  [
            'id'  =>  $this->ticket->id,
            'order' =>  [
              'id'            =>  $this->order->id,
              'total_amount'  =>  '2000'
            ],
          ], 
          'addon_menu'  =>  [
            'id'  =>  $this->addonMenu->id
          ],
          'status'  =>  [
            'id'  =>  $this->status->id
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_addonMenuId_qty_statusId_amount_while_updating()
  {
    $this->json('patch', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons/' . $this->ticketAddon->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "addon_menu_id"        =>  ["The addon menu id field is required."],
          "qty"                  =>  ["The qty field is required."],
          "status_id"            =>  ["The status id field is required."],
          "amount"               =>  ["The amount field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function ticket_addon_updated_successfully()
  {
    $this->disableEH();
    $payload = [
      'id'              =>  $this->ticketAddon->id,
      'ticket_id'       =>  $this->ticketAddon->ticket_id,
      'addon_menu_id'   =>  $this->ticketAddon->addon_menu_id,
      'qty'             =>  '5',
      'description'     =>  $this->ticketAddon->description,
      'status_id'       =>  $this->ticketAddon->status_id,
      'amount'          =>  '2000',
    ];

    $this->json('patch', '/api/orders/' . $this->order->id . '/tickets/' . $this->ticket->id . '/addons/' . $this->ticketAddon->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'ticket_id'       =>  $this->ticketAddon->ticket_id,
          'addon_menu_id'   =>  $this->ticketAddon->addon_menu_id,
          'qty'             =>  '5',
          'description'     =>  $this->ticketAddon->description,
          'status_id'       =>  $this->ticketAddon->status_id,
          'amount'          =>  '2000',
          'ticket'          =>  [
            'id'    =>  $this->ticket->id,
            'order' =>  [
              'id'            =>  $this->order->id,
              'total_amount'  =>  '3000'
            ],
          ], 
          'addon_menu'  =>  [
            'id'  =>  $this->addonMenu->id
          ],
          'status'  =>  [
            'id'  =>  $this->status->id
          ]
        ]
      ]);
  }

}
