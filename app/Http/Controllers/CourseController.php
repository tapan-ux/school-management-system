<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{

  private $mail_controller;
  /**
   * Create a new UserController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('has_permission:course-create')->only(['create', 'store',]);
    $this->middleware('has_permission:course-list')->only(['index']);
    $this->middleware('has_permission:course-edit')->only(['update']);
    $this->middleware('has_permission:course-delete')->only(['destroy']);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $courses = Course::all();
    return view('admin.course.index', compact('courses'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('admin.course.create-edit');
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
        'title'  => 'required',
        'upload_file'  => 'required',
    ]);

    if ($request->hasFile('upload_file')) {
        $imageName = $this->addMultimedia($requestData['upload_file'], $requestData['title'], "file");
        unset($requestData['upload_file']);
        $requestData['upload_file'] = $imageName;
      }

    $requestData['user_id'] = auth()->user()->id;

    $course = Course::create($requestData);

    if (!$course) return redirect()->back()->withError('Error in creating Course');

    return redirect()->route('course.index')->withSuccess('Course Created Successfully');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $course = Course::find($id);
    return view('admin.course.create-edit', compact('course'));
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
        'title'  => 'required',
        'upload_file'  => 'required',
    ]);

    $course = Course::where('id',$id)->update($requestData);

    if (!$course) return redirect()->back()->withError('Error in updating User');
    return redirect()->route('course.index')->withSuccess('Course updated Successfully');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $course = Course::find($id);
    $course->delete();
    return redirect()->back()->withSuccess('Course deleted Successfully');
  }

  protected function addMultimedia($image, $title, $imageType)
  {
    $imageName = str_replace(' ', '_', $title) . '_' . $imageType . '_' . time() . '.' . $image->extension();
    $imagePath = 'courses';
    $imageDir = Storage::disk('public')->putFileAs($imagePath, $image, $imageName);
    return $imageDir;
  }
}
