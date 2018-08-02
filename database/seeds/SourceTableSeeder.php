<?php

use App\Source;
use Illuminate\Database\Seeder;

class SourceTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Source::create(['source'  =>  'Table']);
    Source::create(['source'  =>  'Zomato']);
  }
}
