<?php

namespace Tests\Feature;

use App\Table;
use App\Status;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TableTest extends TestCase
{
  use DatabaseTransactions;

  protected $table, $status;

  public function setUp()
  {
    parent::SetUp();

    $this->table = factory(Table::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);

    $this->status = Status::find(1);

    $this->table->storeImages($this->hotel, [ 
      0 => [
        'image_path' =>  'image_path'
      ]
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/tables')
      ->assertStatus(401); 
  }

  /** @test */
  function tables_fetched_successfully()
  {
    $this->disableEH();
    $this->json('get', '/api/tables', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'id'          =>  $this->table->id,
            'hotel_id'    =>  $this->table->hotel_id,
            'name'        =>  $this->table->name,
            'capacity'    =>  $this->table->capacity,
            'status_id'    => $this->status->id,
            'status'  =>  [
              'id'      =>  $this->status->id,
              'status'  =>  $this->status->status 
            ],
            'images'  =>  [
              0 =>  [
                'image_path' =>  'image_path'
              ]
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_name_capacity_and_statusId()
  {
    $this->json('post', '/api/tables', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "name"        =>  ["The name field is required."],
          "status_id"        =>  ["The status id field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function table_saved_successfully()
  {
    $payload = [
      'name'        =>  'Table 1',
      'capacity'    =>  '4',
      'status_id'   =>  '1',
      'images'  =>  [
        0 =>  [
          'image_path' =>  'image_path'
        ]
      ]
    ];

    $this->json('post', '/api/tables', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'        =>  'Table 1',
          'capacity'    =>  '4',
          'status_id'   =>  '1',
          'images'  =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_table_fetched_successfully()
  {
    $this->json('get', '/api/tables/' . $this->table->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'          =>  $this->table->id,
          'hotel_id'    =>  $this->table->hotel_id,
          'name'        =>  $this->table->name,
          'capacity'    =>  $this->table->capacity,
          'status_id'    => $this->status->id,
          'status'  =>  [
            'id'      =>  $this->status->id,
            'status'  =>  $this->status->status 
          ],
          'images'  =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ],
          'orders'  =>  []
        ]
      ]);  
  }

  /** @test */
  function it_requires_name_capacity_and_statusId_while_updating()
  {
    $this->json('patch', '/api/tables/'. $this->table->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "status_id"        =>  ["The status id field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function table_updated_successfully()
  {
    $this->disableEH();
    $images = $this->table->images;
    $payload = [
      'id'          =>  $this->table->id,
      'hotel_id'    =>  $this->table->hotel_id,
      'name'        =>  'Table 2',
      'capacity'    =>  $this->table->capacity,
      'status_id'   => $this->status->id,
      'images'  =>  [
        0 =>  [
          'id'         =>   $images[0]->id,
          'image_path' =>   'image_path1'
        ]
      ]
    ];

    $this->json('patch', '/api/tables/' . $this->table->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'          =>  $this->table->id,
          'hotel_id'    =>  $this->table->hotel_id,
          'name'        =>  'Table 2',
          'capacity'    =>  $this->table->capacity,
          'status_id'   => $this->status->id,
          'status'  =>  [
            'id'      =>  $this->status->id,
            'status'  =>  $this->status->status 
          ],
          'images'  =>  [
            0 =>  [
              'image_path' =>  'image_path1'
            ]
          ]
        ]
      ]);
  }
}
