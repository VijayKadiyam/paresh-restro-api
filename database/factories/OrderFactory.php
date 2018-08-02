<?php

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
  return [
    'hotel_id'        =>  '1',
    'description'     =>  'description',
    'source_id'       =>  '1' 
  ];
});
