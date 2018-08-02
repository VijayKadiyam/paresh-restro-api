<?php

use App\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
  return [
    'qty'         =>  'qty',
    'description' =>  'description',
    'amount'      =>  '1000'
  ];
});
