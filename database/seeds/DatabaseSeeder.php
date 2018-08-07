<?php

use App\Role;
use App\Source;
use App\Status;
use App\Currency;
use App\Discount;
use App\MenuType;
use App\ContactType;
use App\DiscountType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    Role::Truncate();
    ContactType::truncate();
    MenuType::truncate();
    Currency::truncate();
    Status::truncate();
    Source::truncate();
    Discount::truncate();
    DiscountType::truncate();

    $this->call(RoleTableSeeder::class);
    $this->call(ContactTypeTableSeeder::class);
    $this->call(MenuTypeTableSeeder::class);
    $this->call(CurrencyTableSeeder::class);
    $this->call(StatusTableSeeder::class);
    $this->call(SourceTableSeeder::class);
    $this->call(DiscountSeeder::class);
    $this->call(DiscountTypeSeeder::class);
  }
}
