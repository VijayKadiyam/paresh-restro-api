<?php

namespace Tests\Feature;

use App\User;
use App\Hotel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HotelTest extends TestCase
{
  use DatabaseTransactions;

  public function setUp()
  {
    parent::setUp();

    $hotel = factory(Hotel::class)->create();
    $this->user->hotels()->syncWithOutDetaching($hotel);
  }

  /** @test */
  function user_must_be_logged_in()
  {
    $this->json('post', '/api/hotels')
      ->assertStatus(401);
  }

  /** @test */
  function hotels_fetched_successfully()
  { 
    $this->json('get', '/api/hotels', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          0 =>  [
            'name'    =>  $this->hotel->name,
            'pan_no'  =>  $this->hotel->pan_no,
            'gstn_no'  =>  $this->hotel->gstn_no,
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_userId_name_pan_no_gstn_no()
  {
    $this->json('post', '/api/hotels', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        'errors' => [
          "gstn_no" =>  ["The gstn no field is required."],
          "name"    =>  ["The name field is required."],
          "pan_no"  =>  ["The pan no field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function hotel_saved_successfully_with_phone()
  {
    $payload = [
      'name'      =>  'Badmash Restro',
      'pan_no'    =>  'COIPK0304M',
      'gstn_no'   =>  'GSTNCOIPK0304M',
      'phones'    =>  [
        0 =>  [
          'phone' =>  '9579862371'
        ]
      ],
      'emails'    =>  [
        0 =>  [
          'email' =>  'email@email.com'
        ]
      ],
      'addresses'   =>  [
        0 => [
          'address' =>  'address',
          'state'   =>  'state',
          'state_code' =>  'state_code',
          'pincode' =>  'pincode',
        ]
      ],
      'accounts'  =>  [
        0 => [
          'acc_no'    =>  'acc_no',
          'acc_name'  =>  'acc_name',
          'ifsc_code' =>  'ifsc_code',
          'branch'    =>  'branch',
        ]
      ],
      'images'    =>  [
        0 =>  [
          'image_path' =>  'image_path'
        ]
      ],
    ];
    $this->json('post', '/api/hotels', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'name'    =>  'Badmash Restro',
          'pan_no'  =>  'COIPK0304M',
          'gstn_no' =>  'GSTNCOIPK0304M',
          'phones'   =>  [
            0 =>  [
              'phone' =>  '9579862371'
            ]
          ],
          'emails'   =>  [
            0 =>  [
              'email' =>  'email@email.com'
            ]
          ],
          'addresses'   =>  [
            0 => [
              'address' =>  'address',
              'state'   =>  'state',
              'state_code' =>  'state_code',
              'pincode' =>  'pincode',
            ]
          ],
          'accounts'  =>  [
            0 => [
              'acc_no'    =>  'acc_no',
              'acc_name'  =>  'acc_name',
              'ifsc_code' =>  'ifsc_code',
              'branch'    =>  'branch',
            ]
          ],
          'images'    =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ],
        ]
      ]);
    $hotel = Hotel::where('name', '=', 'Badmash Restro')->first();
    $this->assertCount(1, $hotel->phones);
  }

  /** @test */
  function single_hotel_fetched_successfully()
  {
    $this->json('get', '/api/hotels/' . $this->hotel->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'    =>  $this->hotel->name,
          'pan_no'  =>  $this->hotel->pan_no,
          'gstn_no'  =>  $this->hotel->gstn_no,
          'phones'   =>  [
            0 =>  [
              'phone' =>  '9579862371'
            ]
          ],
          'emails'   =>  [
            0 =>  [
              'email' =>  'email@email.com'
            ]
          ],
          'addresses'   =>  [
            0 => [
              'address' =>  'address',
              'state'   =>  'state',
              'state_code' =>  'state_code',
              'pincode' =>  'pincode',
            ]
          ],
          'accounts'  =>  [
            0 => [
              'acc_no'    =>  'acc_no',
              'acc_name'  =>  'acc_name',
              'ifsc_code' =>  'ifsc_code',
              'branch'    =>  'branch',
            ]
          ],
          'images'    =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ],
        ]
      ]); 
  }

  /** @test */
  function it_requires_userId_name_pan_no_gstn_no_while_updating()
  {
    $this->json('patch', "/api/hotels/". $this->hotel->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        'errors' => [
          "gstn_no" =>  ["The gstn no field is required."],
          "name"    =>  ["The name field is required."],
          "pan_no"  =>  ["The pan no field is required."]
        ],
        "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function hotel_updated_successfully()
  {
    $this->disableEH();
    $phones = $this->hotel->phones;
    $emails = $this->hotel->emails;
    $addresses = $this->hotel->addresses;
    $accounts = $this->hotel->accounts;
    $images = $this->hotel->images;
    $payload = [
      'id'      =>  $this->hotel->id,
      'name'    =>  'Badmash',
      'pan_no'  =>  $this->hotel->pan_no,
      'gstn_no' =>  $this->hotel->gstn_no,
      'phones'  =>  [
        0 =>  [
          'id'      =>  $phones[0]->id,
          'phone'   =>  '9820750142'
        ],
        1 =>  [
          'phone'   =>  '9323828200'
        ]
      ],
      'emails'  =>  [
        0 =>  [
          'id'      =>  $emails[0]->id,
          'email'   =>  'email1@email.com'
        ],
        1 =>  [
          'email'   =>  'email2@email.com'
        ]
      ],
      'addresses'   =>  [
        0 => [
          'id'      =>  $addresses[0]->id,
          'address' =>  'address1' 
        ]
      ],
      'accounts'  =>  [
        0 => [
          'id'      =>  $accounts[0]->id,
          'acc_no'    =>  'acc_no1',
          'acc_name'  =>  'acc_name',
          'ifsc_code' =>  'ifsc_code',
          'branch'    =>  'branch',
        ]
      ],
      'images'  =>  [
        0 =>  [
          'id'      =>  $images[0]->id,
          'image_path'   =>  'image_path1'
        ]
      ]
    ];
    $this->json('patch', "/api/hotels/" . $this->hotel->id, $payload, $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'name'    =>  'Badmash',
          'pan_no'  =>  $this->hotel->pan_no,
          'gstn_no' =>  $this->hotel->gstn_no,
          'phones'  =>  [
            0 =>  [
              'id'      =>  $phones[0]->id,
              'phone'   =>  '9820750142'
            ],
            1 =>  [
              'phone'   =>  '9323828200'
            ]
          ],
          'emails'  =>  [
            0 =>  [
              'id'      =>  $emails[0]->id,
              'email'   =>  'email1@email.com'
            ],
            1 =>  [
              'email'   =>  'email2@email.com'
            ]
          ],
          'addresses'   =>  [
            0 => [
              'id'      =>  $addresses[0]->id,
              'address' =>  'address1' 
            ]
          ],
          'accounts'  =>  [
            0 => [
              'id'      =>  $accounts[0]->id,
              'acc_no'    =>  'acc_no1',
              'acc_name'  =>  'acc_name',
              'ifsc_code' =>  'ifsc_code',
              'branch'    =>  'branch',
            ]
          ],
          'images'  =>  [
            0 =>  [
              'id'      =>  $images[0]->id,
              'image_path'   =>  'image_path1'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function phone_added_successfully()
  {
    $this->hotel->storePhones($this->hotel, [ 
      0 => [
        'phone' =>  '9579862371'
      ]
    ]);
    $this->assertCount(2, $this->hotel->phones);
  }

  /** @test */
  function email_added_successfully()
  {
    $this->hotel->storeEmails($this->hotel, [ 
      0 => [
        'email' =>  'email@email.com'
      ]
    ]);
    $this->assertCount(2, $this->hotel->emails);
  }

  /** @test */
  function address_added_successfully()
  {
    $this->hotel->storeAddresses($this->hotel, [ 
      0 => [
        'address'     =>  'address',
        'state'       =>  'state',
        'state_code'  =>  'state_code',
        'pincode'     =>  'pincode',
      ]
    ]);
    $this->assertCount(2, $this->hotel->addresses);
  }

  /** @test */
  function account_added_successfully()
  {
    $this->hotel->storeAccounts($this->hotel, [ 
      0 => [
        'acc_no'    =>  'acc_no',
        'acc_name'  =>  'acc_name',
        'ifsc_code' =>  'ifsc_code',
        'branch'    =>  'branch',
      ]
    ]);
    $this->assertCount(2, $this->hotel->accounts);
  }
}
