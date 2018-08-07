<?php

namespace Tests\Feature;

use App\Order;
use App\Source;
use App\Discount;
use Tests\TestCase;
use App\DiscountType;
use App\OrderDiscount;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderDiscountTest extends TestCase
{
  use DatabaseTransactions;

  protected $order, $source, $discountType, $discount;

  public function setUp()
  {
    parent::SetUp();

    $this->source = Source::find(1);

    $this->order = factory(Order::class)->create([
      'hotel_id'      =>  $this->hotel->id,
      'source_id'     =>  $this->source->id,
      'total_amount'  =>  '500'
    ]);

    $this->discount = factory(Discount::class)->create([
      'name'    =>  '5%',
      'percent' =>  '5'
    ]);

    $this->discountType = factory(DiscountType::class)->create([
      'type'  =>  'By Amount'
    ]);
  }

  /** @test */
  function order_discounts_fetched_successfully()
  {
    factory(OrderDiscount::class)->create([
      'order_id'        =>  $this->order->id,
      'discount_type_id'=>  $this->discountType->id,
      'discount_id'     =>  $this->discount->id,
      'amount'          =>  '100'
    ]);

    $this->json('get', '/api/orders/' . $this->order->id . '/order-discounts', [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          0 =>  [
            'order_id'        =>  $this->order->id,
            'discount_type_id'=>  $this->discountType->id,
            'discount_id'     =>  $this->discount->id,
            'amount'          =>  '100',
            'order' =>  [
              'id'            =>  $this->order->id,
              'total_amount'  =>  '500'
            ],
            'discount_type'  =>  [
              'id'  =>  $this->discountType->id
            ],
            'discount'  =>  [
              'id'  =>  $this->discount->id
            ]
          ]
        ]
      ]); 
  }

  /** @test */
  function it_requires_discountTypeId_discountId_amount()
  {
    $this->json('post', '/api/orders/' . $this->order->id . '/order-discounts/', [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "discount_type_id" =>  ["The discount type id field is required."],
          "amount"           =>  ["The amount field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function order_discount_saved_successfully()
  {
    $this->disableEH();
    $payload = [
      'order_id'        =>  $this->order->id,
      'discount_type_id'=>  $this->discountType->id,
      'discount_id'     =>  $this->discount->id,
      'amount'          =>  '100'
    ];

    $this->json('post', '/api/orders/' . $this->order->id . '/order-discounts', $payload, $this->headers)
      ->assertStatus(201)
      ->assertJson([
        'data'  =>  [
          'order_id'        =>  $this->order->id,
          'discount_type_id'=>  $this->discountType->id,
          'discount_id'     =>  $this->discount->id,
          'amount'          =>  '100',
          'order' =>  [
            'id'            =>  $this->order->id,
            'total_amount'  =>  '500'
          ],
          'discount_type'  =>  [
            'id'  =>  $this->discountType->id
          ],
          'discount'  =>  [
            'id'  =>  $this->discount->id
          ]
        ]
      ]);
  }

  /** @test */
  function single_order_discount_fetched_successfully()
  {
    $orderDiscount = factory(OrderDiscount::class)->create([
      'order_id'        =>  $this->order->id,
      'discount_type_id'=>  $this->discountType->id,
      'discount_id'     =>  $this->discount->id,
      'amount'          =>  '100'
    ]);

    $this->json('get', '/api/orders/' . $this->order->id . '/order-discounts/' . $orderDiscount->id, [], $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'id'              =>  $orderDiscount->id,
          'order_id'        =>  $this->order->id,
          'discount_type_id'=>  $this->discountType->id,
          'discount_id'     =>  $this->discount->id,
          'amount'          =>  '100',
          'order' =>  [
            'id'            =>  $this->order->id,
            'total_amount'  =>  '500'
          ],
          'discount_type'  =>  [
            'id'  =>  $this->discountType->id
          ],
          'discount'  =>  [
            'id'  =>  $this->discount->id
          ]
        ]
      ]);
  }

  /** @test */
  function it_requires_discountTypeId_discountId_amount_while_updating()
  {
    $orderDiscount = factory(OrderDiscount::class)->create([
      'order_id'        =>  $this->order->id,
      'discount_type_id'=>  $this->discountType->id,
      'discount_id'     =>  $this->discount->id,
      'amount'          =>  '100'
    ]);

    $this->json('patch', '/api/orders/' . $this->order->id . '/order-discounts/' . $orderDiscount->id, [], $this->headers)
      ->assertStatus(422)
      ->assertExactJson([
        "errors"  =>  [
          "discount_type_id" =>  ["The discount type id field is required."],
          "amount"           =>  ["The amount field is required."],
        ],
        "message" =>  "The given data was invalid."
      ]); 
  }

  /** @test */
  function order_discount_updated_successfully()
  {
    $orderDiscount = factory(OrderDiscount::class)->create([
      'order_id'        =>  $this->order->id,
      'discount_type_id'=>  $this->discountType->id,
      'discount_id'     =>  $this->discount->id,
      'amount'          =>  '100'
    ]);
    $orderDiscount->amount = '200';

    $this->json('patch', '/api/orders/' . $this->order->id . '/order-discounts/' . $orderDiscount->id, $orderDiscount->toArray(), $this->headers)
      ->assertStatus(200)
      ->assertJson([
        'data'  =>  [
          'order_id'        =>  $this->order->id,
          'discount_type_id'=>  $this->discountType->id,
          'discount_id'     =>  $this->discount->id,
          'amount'          =>  '200',
          'order' =>  [
            'id'            =>  $this->order->id,
            'total_amount'  =>  '500'
          ],
          'discount_type'  =>  [
            'id'  =>  $this->discountType->id
          ],
          'discount'  =>  [
            'id'  =>  $this->discount->id
          ]
        ]
      ]);
  }

}
