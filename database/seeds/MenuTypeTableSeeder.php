<?php

use App\MenuType;
use Illuminate\Database\Seeder;

class MenuTypeTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    MenuType::create(['type'  =>  'Appetizers']);
    MenuType::create(['type'  =>  'Breakfast']);
    MenuType::create(['type'  =>  'Continental / Fast Food']);
    MenuType::create(['type'  =>  'Veg Entree']);
    MenuType::create(['type'  =>  'Non Veg Entree']);
    MenuType::create(['type'  =>  'Rice']);
    MenuType::create(['type'  =>  'Extras']);
  }
}
