<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\PermissionModule;
use App\Models\PermissionRole;

class RoleController extends Controller
{

  public function __construct()
  {
    $this->middleware('has_permission:role-create')->only(['create','store',]);
    $this->middleware('has_permission:role-list')->only(['index']);
    $this->middleware('has_permission:role-edit')->only(['update']);
    $this->middleware('has_permission:role-delete')->only(['destroy']);
  }

  public function index(Request $request)
  {
    $roles = Role::all();
    return view('admin.role.index', compact('roles'));
  }

  public function create(Request $request){
    $permission_modules = PermissionModule::orderBy('name')->get();
    return view('admin.role.create-edit', compact('permission_modules'));
  }

  public function store(RoleRequest $request)
  {
    if($request->name == 'Superadmin') return back()->withErrors(['name' => 'Name should be except "Superadmin"']);
    $role = Role::create(['name' => $request->name]);
    foreach($request->permissions as $permission){
        $permission_role = PermissionRole::create(['role_id' => $role->id, 'permission_id' => $permission]);
    }
    return redirect()->route('roles.index')->withSuccess('Role added Successfully');
  }

  public function edit($id){

    $role = Role::find($id);
    $permission_modules = PermissionModule::orderBy('name')->get();
    $role_permissions = $role->permissions ? $role->permissions->pluck('id')->toArray() : [];
    return view('admin.role.create-edit', compact('role', 'permission_modules', 'role_permissions'));
  }

  public function update(RoleRequest $request, $id)
  {
    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);
    PermissionRole::where('role_id',$id)->delete();
    foreach($request->permissions as $permission){
        $permission_role = PermissionRole::create(['role_id' => $role->id, 'permission_id' => $permission]);
    }
    return redirect()->route('roles.index')->withSuccess('Role updated Successfully');
  }

  public function destroy($id)
  {
    Role::find($id)->delete();
    PermissionRole::where('role_id',$id)->delete();
    return redirect()->back()->withSuccess('Role deleted Successfully');
  }
}
