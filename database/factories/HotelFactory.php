<?php

use App\User;
use App\Hotel;
use Faker\Generator as Faker;

$factory->define(Hotel::class, function (Faker $faker) {
  return [
    'name'    =>  'Badmash Restro',
    'pan_no'  =>  'COIPK0304M',
    'gstn_no' =>  'GSTN0304M'
  ];
});
