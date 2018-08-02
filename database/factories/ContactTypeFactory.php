<?php

use App\ContactType;
use Faker\Generator as Faker;

$factory->define(ContactType::class, function (Faker $faker) {
  return [
    'type'  =>  'Supplier'
  ];
});
