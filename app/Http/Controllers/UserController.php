<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{

  private $mail_controller;
  /**
   * Create a new UserController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('has_permission:user-create')->only(['create', 'store',]);
    $this->middleware('has_permission:user-list')->only(['index']);
    $this->middleware('has_permission:user-edit')->only(['update']);
    $this->middleware('has_permission:user-delete')->only(['destroy']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $users = User::notSuperadmin()->get();
    return view('admin.user.index', compact('users'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $roles = Role::all();
    return view('admin.user.create-edit', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserRequest $request)
  {

    $requestData = $request->validated();

    $user = User::create($requestData);

    if (!$user) {
      return redirect()->back()->withError('Error in creating User');
    } else {
      $this->mail_controller->send_verification_mail($requestData['email'], $user->id, $requestData['name']);
    }
    return redirect()->route('user.index')->withSuccess('User Created Successfully and mail is sent to provided email');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::find($id);
    return view('admin.user.show', compact('user'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = User::find($id);
    $roles = Role::all();
    return view('admin.user.create-edit', compact('user', 'roles'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UserRequest $request, $id)
  {
    $requestData = $request->validated();

    $user->where('id',$id)->update($requestData);

    if (!$user) return redirect()->back()->withError('Error in updating User');
    if($request->update_profile) return redirect()->route('user.update_profile')->withSuccess('Profile updated Successfully');
    return redirect()->route('user.index')->withSuccess('User updated Successfully');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $user = User::find($id);
    $user->delete();
    return redirect()->back()->withSuccess('User deleted Successfully');
  }
}
