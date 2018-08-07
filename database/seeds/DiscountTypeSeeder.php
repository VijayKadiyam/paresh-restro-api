<?php

use App\DiscountType;
use Illuminate\Database\Seeder;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DiscountType::create(['type'  =>  'By Percent']);
      DiscountType::create(['type'  =>  'By Amount']);
    }
}
