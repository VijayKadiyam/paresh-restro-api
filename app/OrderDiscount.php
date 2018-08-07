<?php

namespace App;

use App\Order;
use App\Discount;
use App\DiscountType;
use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
  protected $fillable = [
    'discount_type_id', 'discount_id', 'amount'
  ];

  /*
   * To store a new order discount
   *
   *@
   */
  public function store($order)
  {
    $order->order_discounts()->save($this);

    return $this;
  }

  /*
   * An order discount belongs to order
   *
   *@
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /*
   * An order discount belongs to discount
   *
   *@
   */
  public function discount()
  {
    return $this->belongsTo(Discount::class);
  }

  /*
   * To reform a order discount data
   *
   *@
   */
  public function reform($order)
  {
    $this->update(request()->all()); 

    return $this;
  }

  /*
   * An order discount belongs to discount type
   *
   *@
   */
  public function discount_type()
  {
    return $this->belongsTo(DiscountType::class);
  }
}
