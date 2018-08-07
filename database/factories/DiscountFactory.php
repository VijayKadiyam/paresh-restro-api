<?php

use App\Discount;
use Faker\Generator as Faker;

$factory->define(Discount::class, function (Faker $faker) {
  return [
    'name'    =>  '5 Percent',
    'percent' =>  '5'
  ];
});
