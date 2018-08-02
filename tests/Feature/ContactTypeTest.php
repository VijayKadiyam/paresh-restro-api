<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\ContactType;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactTypeTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function contact_types_fetched_successfully()
  {
    factory(ContactType::class)->create([
      'type'  =>  'Supplier'
    ]);

    $this->json('get', '/api/contact-types')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'type'  =>  'Supplier'
          ]
        ]
      ]);
  }
}
