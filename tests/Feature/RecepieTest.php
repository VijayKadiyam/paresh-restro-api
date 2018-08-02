<?php

namespace Tests\Feature;

use App\Recepie;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecepieTest extends TestCase
{
  use DatabaseTransactions;

  protected $recepie;

  public function setUp()
  {
    parent::SetUp();

    $this->recepie = factory(Recepie::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);
    $this->recepie->storeImages($this->hotel, [ 
      0 => [
        'image_path' =>  'image_path'
      ]
    ]);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/recepies')
      ->assertStatus(401); 
  }

  /** @test */
  function recepies_fetched_successfully()
  {
    $this->json('get', '/api/recepies', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'id'          =>  $this->recepie->id,
            'hotel_id'    =>  $this->recepie->hotel_id,
            'name'        =>  $this->recepie->name,
            'description' =>  $this->recepie->description,
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
  function it_requires_name_and_description()
  {
    $this->json('post', '/api/recepies', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "description" =>  ["The description field is required."],
          "name"        =>  ["The name field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function recepie_saved_successfully()
  {
    $payload = [
      'name'        =>  'Dal Fry',
      'description' =>  'Tasty Food',
      'images'    =>  [
        0 =>  [
          'image_path' =>  'image_path'
        ]
      ],
    ];

    $this->json('post', '/api/recepies', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'        =>  'Dal Fry',
          'description' =>  'Tasty Food',
          'images'    =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_recepie_fetched_successfully()
  {
    $this->json('get', '/api/recepies/' . $this->recepie->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'hotel_id'    =>  $this->recepie->hotel_id,
          'name'        =>  $this->recepie->name,
          'description' =>  $this->recepie->description,
          'images'  =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ]
        ]
      ]);  
  }

  /** @test */
  function it_requires_name_and_description_while_updtaing()
  {
    $this->json('patch', '/api/recepies/' . $this->recepie->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "description" =>  ["The description field is required."],
          "name"        =>  ["The name field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function recepie_updated_successfully()
  {
    $images = $this->recepie->images;
    
    $payload = [
      'id'          =>  $this->recepie->id,
      'name'        =>  "Chicken",
      'description' =>  $this->recepie->description,
      'images'      =>  [
        0 =>  [
          'id'         => $images[0]->id,
          'image_path' =>  'image_path1'
        ]
      ],
    ];

    $this->json('patch', '/api/recepies/' . $this->recepie->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'          =>  $this->recepie->id,
          'hotel_id'    =>  $this->recepie->hotel_id,
          'name'        =>  'Chicken',
          'description' =>  $this->recepie->description,
          'images'  =>  [
            0 =>  [
              'id'         => $images[0]->id,
              'image_path' =>  'image_path1'
            ]
          ]
        ]
      ]);
  }
}
