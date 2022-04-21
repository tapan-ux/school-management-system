<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;


class StudentController extends Controller
{

  private $mail_controller;
  /**
   * Create a new UserController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('has_permission:student-create')->only(['create', 'store',]);
    $this->middleware('has_permission:student-list')->only(['index']);
    $this->middleware('has_permission:student-edit')->only(['update']);
    $this->middleware('has_permission:student-delete')->only(['destroy']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $students = Student::all();
    return view('admin.student.index', compact('students'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.student.create-edit');
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
        'unique_id' => 'required|unique:students,unique_id',
        'email' => 'required|email|unique:students,email',
        'phone_number' => 'required',
        'gender' => 'required',
        'address' => 'sometimes|nullable',
        'dob' => 'sometimes|nullable',
        'blood_group' => 'sometimes|nullable',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:10240',
    ]);

    $role = Role::where('name','Student')->first();
    $student = Student::create($requestData);
    $user =  User::create([
        'name' => $requestData['name'],
        'email' => $requestData['email'],
        'password' => bcrypt('password'),
        'role_id' => $role->id
    ]);

    if (!$student) return redirect()->back()->withError('Error in creating User');

    return redirect()->route('student.index')->withSuccess('Student Created Successfully');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $student = Student::find($id);
    return view('admin.student.show', compact('student'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $student = Student::find($id);
    return view('admin.student.create-edit', compact('student'));
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
        'email' => 'required|email',
        'phone_number' => 'required',
        'gender' => 'required',
        'address' => 'sometimes|nullable',
        'dob' => 'sometimes|nullable',
        'blood_group' => 'sometimes|nullable',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:10240',
    ]);

    $student = Student::where('id',$id)->update($requestData);

    if (!$student) return redirect()->back()->withError('Error in updating User');
    return redirect()->route('student.index')->withSuccess('Student updated Successfully');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $student = Student::find($id);
    $student->delete();
    return redirect()->back()->withSuccess('Student deleted Successfully');
  }
}
