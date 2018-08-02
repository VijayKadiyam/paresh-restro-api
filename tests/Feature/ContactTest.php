<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactTest extends TestCase
{
  use DatabaseTransactions; 

  protected $contact;

  public function setUp()
  {
    parent::setUp(); 

    $this->contact = factory(Contact::class)->create([
      'hotel_id'  =>  $this->hotel->id
    ]);
  } 

  /** @test */
  function contacts_fetched_successfully()
  { 
    $this->json('get', '/api/contacts', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data' => [
          0 =>  [
            'hotel_id'=>  $this->hotel->id,
            'name'    =>  $this->contact->name,
            'pan_no'  =>  $this->contact->pan_no,
            'gstn_no'  =>  $this->contact->gstn_no,
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_companyName_name_panNo_gstnNo()
  {
    $this->json('post', '/api/contacts', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "company_name"  =>  ["The company name field is required."],
          "gstn_no"       =>  ["The gstn no field is required."],
          "name"          =>  ["The name field is required."],
          "pan_no"        =>  ["The pan no field is required."],
          "types"         =>  ["The types field is required."]
          ],
          "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function contact_saved_successfully()
  {
    $this->disableEH();
    $payload = [
      'company_name'  =>  'AaiBuzz',
      'name'          =>  'name',
      'pan_no'        =>  'COIPK0403M',
      'gstn_no'       =>  'GSTNCOIPK0304M',
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
      'types' =>  [
        0 =>  '1'
      ],
      'images'    =>  [
        0 =>  [
          'image_path' =>  'image_path'
        ]
      ],
    ];

    $this->json('post', '/api/contacts', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'company_name'  =>  'AaiBuzz',
          'name'          =>  'name',
          'pan_no'        =>  'COIPK0403M',
          'gstn_no'       =>  'GSTNCOIPK0304M',
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
          'types' =>  [
            0 =>  [
              'type'  =>  'Supplier'
            ]
          ],
          'images'    =>  [
            0 =>  [
              'image_path' =>  'image_path'
            ]
          ]
        ]
      ]);
  }

  /** @test */
  function single_contact_fetched_successfully()
  {
    $this->json('get', '/api/contacts/' . $this->contact->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'hotel_id'=>  $this->hotel->id,
          'name'    =>  $this->contact->name,
          'pan_no'  =>  $this->contact->pan_no,
          'gstn_no'  =>  $this->contact->gstn_no
        ]
      ]);
  }

  /** @test */
  function it_requires_companyName_name_panNo_gstnNo_while_updating()
  {
    $this->json('patch', '/api/contacts/' . $this->contact->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "company_name"  =>  ["The company name field is required."],
          "gstn_no"       =>  ["The gstn no field is required."],
          "name"          =>  ["The name field is required."],
          "pan_no"        =>  ["The pan no field is required."],
          "types"         =>  ["The types field is required."]
          ],
          "message" =>  "The given data was invalid."
      ]);
  }

  /** @test */
  function contact_updated_successfully()
  {
    $this->disableEH();
    $this->contact->name = "Sanjay";
    $this->contact->types = [
      0 =>  '1'
    ];
    $this->json('patch', '/api/contacts/' . $this->contact->id, $this->contact->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'hotel_id'=>  $this->hotel->id,
          'name'    =>  "Sanjay",
          'pan_no'  =>  $this->contact->pan_no,
          'gstn_no'  =>  $this->contact->gstn_no,
        ]
      ]);
  }
}
