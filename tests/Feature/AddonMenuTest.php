<?php

namespace Tests\Feature;

use App\Addon;
use App\AddonMenu;
use Tests\TestCase;
use App\AddonMenuPrice;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddonMenuTest extends TestCase
{
  use DatabaseTransactions;
  
  protected $addonMenu, $addonMenuPrice, $hotel, $addon;

  public function setUp()
  {
    parent::setUp();

    $this->addon = factory(Addon::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->addonMenu = factory(AddonMenu::class)->create([
      'hotel_id'    =>  $this->hotel->id,
      'addon_id'  =>  $this->addon->id
    ]);

    $this->addonMenuPrice = AddonMenuPrice::create([
      'addon_menu_id' =>  $this->addonMenu->id,
      'price'       =>  '500',
      'currency_id' =>  '1'
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/addon-menus')
      ->assertStatus(401); 
  }

  /** @test */
  function addon_menus_fetched_successfully()
  {
    $this->json('get', '/api/addon-menus', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'hotel_id'    =>  $this->hotel->id,
            'addon_id'  =>  $this->addon->id
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_addonId()
  {
    $this->json('post', '/api/addon-menus', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "addon_id"  =>  ["The addon id field is required."],
          "price"       =>  ["The price field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function addon_menu_saved_successfully()
  {
    $payload = [
      'addon_id'    =>  $this->addon->id,
      'price'       =>  [
        'price'       =>  '500',
        'currency_id' =>  '1'
      ]
    ];

    $this->json('post', '/api/addon-menus', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'addon_id'    =>  $this->addon->id,
          'prices'      =>  [
            0 => [
              'price'       =>  '500',
              'currency_id' =>  '1'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_addon_menu_fetched_successfully()
  {
    $this->disableEH();

    $this->json('get', '/api/addon-menus/' . $this->addonMenu->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'addon_id'    =>  $this->addon->id,
          'prices'       =>  [
            0 => [
              'price'       =>  '500',
              'currency_id' =>  '1'
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_addonId_while_updating()
  {
    $this->json('patch', '/api/addon-menus/' . $this->addonMenu->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "addon_id"  =>  ["The addon id field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

}
