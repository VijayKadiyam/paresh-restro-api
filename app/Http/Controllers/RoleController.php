<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
  /*
   * To fetch all the roles
   *
   *@
   */
  public function index()
  { 
    $roles = Role::latest()->get();

    return response()->json([
      'data'  =>  $roles
    ]);
  }

  /*
   * To store a new role
   *
   *@
   */
  public function store(Request $request)
  {
    $request->validate([
      'role'  =>  'required'
    ]);

    $role = new Role($request->all());
    $role->save();

    return response()->json([
      'data'  =>  $role->toArray()
    ], 201);
  }

  /*
   * To get a single role
   *
   *@
   */
  public function show(Role $role)
  {
    return response()->json([
      'data'  =>  $role
    ]);
  }

  /*
   * To update a role
   *
   *@
   */
  public function update(Role $role, Request $request)
  {
    $role->update($request->all());

    return response()->json([
      'data'  =>  $role
    ]);
  } 
}
