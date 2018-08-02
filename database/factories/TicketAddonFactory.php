<?php

use App\TicketAddon;
use Faker\Generator as Faker;

$factory->define(TicketAddon::class, function (Faker $faker) {
  return [
    'qty'         =>  'qty',
    'description' =>  'description',
    'amount'      =>  '1000'
  ];
});
