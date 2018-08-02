<?php

use App\MenuType;
use Faker\Generator as Faker;

$factory->define(MenuType::class, function (Faker $faker) {
  return [
    'type'  =>  'Desert'
  ];
});
