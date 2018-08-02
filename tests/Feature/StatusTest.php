<?php

namespace Tests\Feature;

use App\Status;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatusTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  function statuses_fetched_successfully()
  {
    factory(Status::class)->create([
      'status'  =>  'Yes'
    ]);

    $this->json('get', '/api/statuses')
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'status'  =>  'Yes'
          ]
        ]
      ]);
  }
}
