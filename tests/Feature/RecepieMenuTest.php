<?php

namespace Tests\Feature;

use App\Hotel;
use App\Recepie;
use App\MenuType;
use Tests\TestCase;
use App\RecepieMenu;
use App\RecepieMenuPrice;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecepieMenuTest extends TestCase
{
  use DatabaseTransactions;
  
  protected $recepieMenu, $recepie, $type;

  public function setUp()
  {
    parent::setUp();

    $this->recepie = factory(Recepie::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->type = MenuType::find(1);

    $this->recepieMenu = factory(RecepieMenu::class)->create([
      'hotel_id'    =>  $this->hotel->id,
      'recepie_id'  =>  $this->recepie->id,
      'type_id'     =>  $this->type->id
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/recepie-menus')
      ->assertStatus(401); 
  }

  /** @test */
  function recepie_menus_fetched_successfully()
  {
    $this->json('get', '/api/recepie-menus', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'hotel_id'    =>  $this->hotel->id,
            'recepie_id'  =>  $this->recepie->id,
            'type_id'     =>  $this->type->id
          ]
        ]
      ]); 
  }
  
  /** @test */
  function it_requires_recepieId_and_typeId()
  {
    $this->json('post', '/api/recepie-menus', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "recepie_id"  =>  ["The recepie id field is required."],
          "type_id"     =>  ["The type id field is required."],
          "price"       =>  ["The price field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function recepie_menu_saved_successfully()
  {
    $this->disableEH();
    
    $payload = [
      'recepie_id'  =>  $this->recepie->id,
      'type_id'     =>  $this->type->id,
      'price'       =>  [
        'price'       =>  '500',
        'currency_id' =>  '1'
      ]
    ];

    $this->json('post', '/api/recepie-menus', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'recepie_id'  =>  $this->recepie->id,
          'type_id'     =>  $this->type->id,
          'prices'       =>  [
            0 => [
              'price'       =>  '500',
              'currency_id' =>  '1'
            ]
          ],
          'recepie' =>  [
            'id'  =>  $this->recepie->id
          ],
          'type'  =>  [
            'id'  =>  $this->type->id
          ]
        ]
      ]);
  }

  /** @test */
  function single_recepie_menu_fetched_successfully()
  {
    $this->json('get', '/api/recepie-menus/' . $this->recepieMenu->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'recepie_id'  =>  $this->recepie->id,
          'type_id'     =>  $this->type->id
        ]
      ]); 
  }

  /** @test */
  function it_requires_recepieId_and_typeId_while_updating()
  {
    $this->json('patch', '/api/recepie-menus/' . $this->recepieMenu->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "recepie_id"  =>  ["The recepie id field is required."],
          "type_id"     =>  ["The type id field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function recepie_menu_updated_successfully()
  {
    $recepieMenuPrice = RecepieMenuPrice::create([
      'recepie_menu_id' =>  $this->recepieMenu->id,
      'price'       =>  '500',
      'currency_id' =>  '1'
    ]);

    $payload = [
      'id'  =>  $this->recepieMenu->id,
      'recepie_id'  =>  '3',
      'type_id'     =>  $this->type->id,
      'price'       =>  [
        'recepie_menu_id' =>  $this->recepieMenu->id,
        'price'           =>  '5000',
        'currency_id'     =>  '1'
      ]
    ];

    $this->json('patch', '/api/recepie-menus/' . $this->recepieMenu->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'          =>  $this->recepieMenu->id,
          'recepie_id'  =>  '3',
          'type_id'     =>  $this->type->id,
          'prices'      =>  [
            0 =>  [
              'price'       =>  '500',
              'currency_id' =>  '1'
            ],
            1 =>  [
              'price'       =>  '5000',
              'currency_id' =>  '1'
            ]
          ]
        ]
      ]);
  }
}
