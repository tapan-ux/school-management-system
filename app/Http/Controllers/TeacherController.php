<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

  private $mail_controller;
  /**
   * Create a new UserController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('has_permission:teacher-create')->only(['create', 'store',]);
    $this->middleware('has_permission:teacher-list')->only(['index']);
    $this->middleware('has_permission:teacher-edit')->only(['update']);
    $this->middleware('has_permission:teacher-delete')->only(['destroy']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $teachers = Teacher::all();
    return view('admin.teacher.index', compact('teachers'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.teacher.create-edit');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $requestData = $request->validate([
        'name'  => 'required|',
        'unique_id' => 'required|unique:teachers,unique_id',
        'email' => 'required|email|unique:teachers,email',
        'phone_number' => 'required',
        'gender' => 'required',
        'address' => 'sometimes|nullable',
        'dob' => 'sometimes|nullable',
        'blood_group' => 'sometimes|nullable',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:10240',
    ]);

    $role = Role::where('name','Teacher')->first();
    $teacher = Teacher::create($requestData);
    $user =  User::create([
        'name' => $requestData['name'],
        'email' => $requestData['email'],
        'password' => bcrypt('password'),
        'role_id' => $role->id
    ]);

    if (!$teacher) return redirect()->back()->withError('Error in creating Teacher');

    return redirect()->route('teacher.index')->withSuccess('Teacher Created Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $teacher = Teacher::find($id);
    return view('admin.teacher.show', compact('teacher'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $teacher = Teacher::find($id);
    return view('admin.teacher.create-edit', compact('user'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $requestData = $request->validate([
        'name'  => 'required|',
        'unique_id' => 'required',
        'email' => 'required',
        'phone_number' => 'required',
        'gender' => 'required',
        'address' => 'sometimes|nullable',
        'dob' => 'sometimes|nullable',
        'blood_group' => 'sometimes|nullable',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:10240',
    ]);

    $teacher = Teacher::where('id',$id)->update($requestData);

    if (!$teacher) return redirect()->back()->withError('Error in updating User');
    return redirect()->route('teacher.index')->withSuccess('Teacher updated Successfully');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $teacher = Teacher::find($id);
    $teacher->delete();
    return redirect()->back()->withSuccess('Teacher deleted Successfully');
  }
}
