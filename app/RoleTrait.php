<?php

namespace App;

/*Models*/
use App\Role;

trait RoleTrait
{
  /**
   * A user belongs to many roles
   *
   * @ 
   */
  public function roles()
  {
    return $this->belongsToMany(Role::class)
      ->withTimestamps();
  }

  /**
   * Assign a role to a user
   *
   * @ 
   */
  public function assignRole($role)
  {
    if(!$role instanceof Role)
      $role = Role::where('role', '=', $role)->first();
    return $this->roles()->syncWithoutDetaching($role);
  }

  /**
   * Check if the user has role
   *
   * @ 
   */
  public function hasRole($role)
  {
    return $this->roles ? in_array($role, $this->roles->pluck('role')->toArray()) : false;
  }
}