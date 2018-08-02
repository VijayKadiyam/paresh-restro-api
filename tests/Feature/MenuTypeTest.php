<?php

namespace Tests\Feature;

use App\MenuType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MenuTypeTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function menu_types_fetched_successfully()
  {
    factory(MenuType::class)->create([
      'type'  =>  'Desert'
    ]);

    $this->json('get', '/api/menu-types')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'type'  =>  'Desert'
          ]
        ]
      ]);
  }
}
