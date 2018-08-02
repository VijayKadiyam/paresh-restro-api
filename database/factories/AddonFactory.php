<?php

use App\Addon;
use Faker\Generator as Faker;

$factory->define(Addon::class, function (Faker $faker) {
  return [
    'hotel_id'    =>  '1',
    'name'        =>  'Add  on',
    'description' =>  'Add on description'  
  ];
});
