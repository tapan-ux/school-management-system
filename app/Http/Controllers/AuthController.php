<?php

namespace App\Http\Controllers;

use App\Models\MedicineStock;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

  public function login(Request $request)
  {
    if ($request->isMethod('post')) {
      $data = $request->input();

      if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']]))
        return redirect()->route('admin.dashboard')
          ->with('success', 'Login Successfull');

      return redirect()->route('admin.login')->with('flash_error', 'Invalid Username and Password');
    }
    if (Auth::check()) return redirect()->route('admin.dashboard');

    return view('admin.auth.loginPage')->with('flash_error', 'Please Login to Access');
  }

  public function logout(Request $request)
  {
    $request->session()->flush();
    return redirect()->route('admin.login')->with('flash_success', 'Logout Successful');
  }


  public function dashboard()
  {
    $students = Student::all();
    $teachers = Teacher::all();
    return view('admin.dashboard.index', compact('students','teachers'));
  }

  public function update_profile()
  {
    $user = User::find(auth()->user()->id);
    return view('admin.user.user-profile', compact('user'));
  }

  public function change_password()
  {
    return view('admin.user.change_password');
  }

  public function update_password(Request $request)
  {
    $validatedData = $request->validate([
      'old_password' => 'required',
      'password' => 'required|min:8|max:50|confirmed',
    ], [
      'old_password.required' => 'Old Password is required',
      'password.required' => 'New Password is required',
      'password.min' => 'Password should be of 8 characters',
      'password.max' => 'Password should be of exceed 50 characters',
      'password.confirmed' => 'Confirm Password did not match',
    ]);
    // Check if Old password does not match
    if (!password_verify($validatedData['old_password'], auth()->user()->password)) {
      return redirect()->route('user.change_password')->withErrors(['old_password' => 'Old Password didn\'t match.']);
    }
    // Check if new password matches old password
    if (password_verify($validatedData['password'], auth()->user()->password)) {
      return redirect()->route('user.change_password')->withErrors(['password' => 'You cannot use the same password.']);
    }
    $user = User::find(auth()->user()->id);
    $user->update([
      'password' => bcrypt($validatedData['password'])
    ]);
    return redirect()->route('user.change_password')->with('flash_success', 'Password Changed Successfully');
  }
}
