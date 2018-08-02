<?php

use App\Table;
use Faker\Generator as Faker;

$factory->define(Table::class, function (Faker $faker) {
  return [
    'hotel_id'  =>  '1',
    'name'      =>  'Table Name',
    'capacity'  =>  'Capacity',
    'status_id' =>  '1'
  ];
});
