<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginFormRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function login(AdminLoginFormRequest $request)
    {

        $input = $request->all();

        $status = Auth::guard('admin')->attempt([
            'login' => $input['username'],
            'password' => $input['password']
        ]);

        if ($status) {
            Toastr::success('Login Successfully');
            return redirect()->route('admin.dashboard');
        } else {
            Toastr::error('Invalid credentials');
            return redirect()->route('admin.get.login');
        }
    }

    public function logout()
    {

        Toastr::success('Logout successfully');
        Auth::guard('admin')->logout();
        return redirect()->route('admin.get.login');
    }

    public function getProfile()
    {

        $admin = Auth::guard('admin')->user();

        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'login.required' => 'Login is required',
            'email.required' => 'Email is required',
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ]);

        $admin = Admin::find(Auth::guard('admin')->user()->id);
        $admin->login = $request->input('login');
        $admin->email = $request->input('email');
        $admin->first_name = $request->input('first_name');
        $admin->last_name = $request->input('last_name');

        // Only update password if it is provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        // Refresh the authenticated admin user in the session
        Auth::guard('admin')->setUser($admin);

        Toastr::success('Profile updated successfully');

        if($request->has('close') && $request->input('close') == 1){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('admin.get.profile');
        }
    }

}
