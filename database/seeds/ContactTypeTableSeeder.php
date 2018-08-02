<?php

use App\ContactType;
use Illuminate\Database\Seeder;

class ContactTypeTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    ContactType::create(['type'   =>  'Supplier']);
    ContactType::create(['type'   =>  'Customer']);
  }
}
