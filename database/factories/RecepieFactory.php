<?php

use App\Recepie;
use Faker\Generator as Faker;

$factory->define(Recepie::class, function (Faker $faker) {
  return [
    'hotel_id'    =>  '1',
    'name'        =>  'Palak Paneer',
    'description' =>  'Hot item'  
  ];
});
