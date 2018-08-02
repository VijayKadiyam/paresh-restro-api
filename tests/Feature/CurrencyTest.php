<?php

namespace Tests\Feature;

use App\Currency;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurrencyTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function currencies_fetched_successfully()
  {
    factory(Currency::class)->create([
      'currency'  =>  'INR'
    ]);

    $this->json('get', '/api/currencies')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'currency'  =>  'INR'
          ]
        ]
      ]);
  }
}
