<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Role::create(['role'  =>  'super_admin']);
    Role::create(['role'  =>  'admin']);
    Role::create(['role'  =>  'chef']);
  }
}
