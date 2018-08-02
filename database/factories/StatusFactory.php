<?php

use App\Status;
use Faker\Generator as Faker;

$factory->define(Status::class, function (Faker $faker) {
  return [
    'status'  =>  'Yes'
  ];
});
