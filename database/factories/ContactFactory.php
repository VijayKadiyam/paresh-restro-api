<?php

use App\Hotel;
use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
  return [
    'hotel_id'      =>  '1',
    'company_name'  =>  'Aaibuzz',
    'name'          =>  'Vijay',
    'pan_no'        =>  'COIPK0304M',
    'gstn_no'       =>  'COIPGSTN'
  ];
});
