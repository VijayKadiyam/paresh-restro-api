<?php

use App\DiscountType;
use Faker\Generator as Faker;

$factory->define(DiscountType::class, function (Faker $faker) {
  return [
    'type'  =>  'By percent'
  ];
});
