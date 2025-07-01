<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\RegisterFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterFormRequest $request)
    {

        $input = $request->all();

        $input['password'] = Hash::make('123456');

        User::create([
            'dealership_name' => $input["dealer_name"],
            'gst_number' => $input["gst_no"],
            'name' => $input['full_name'],
            'address' => $input['address'],
            'city' => $input['city'],
            'zip' => $input['pincode'],
            'state' => $input['state'],
            'phone' => $input['phone'],
            'mobile' => $input['mobile'],
            'email' => $input['email'],
            'is_activated' => 1,
            'activated_at' => date('Y-m-d H:i:s'),
            'username' => $input['email'],
            'password' => $input['password'],
        ]);

        Toastr::success('Registered successfully');
        return redirect()->route('get.login');
    }

    public function login(LoginFormRequest $request)
    {

        $input = $request->all();
        $phone = $input['mobile'];
        $password = '123456';

        // Find user by phone
        $user = User::withTrashed()->where('phone', $phone)->first();

        if (!$user) {
            Toastr::error('User not found.');
            return redirect()->route('get.login');
        }

        // Check if user is deleted
        if ($user->trashed()) {
            Toastr::error('This account has been deleted. Contact admin to restore.');
            return redirect()->route('get.login');
        }

        // Check if user is deactivated
        if (!$user->is_activated) {
            Toastr::error('This account is deactivated. Contact admin to activate.');
            return redirect()->route('get.login');
        }

        // Check password manually
        if (!Hash::check($password, $user->password)) {
            Toastr::error('Invalid credentials.');
            return redirect()->route('get.login');
        }

        $user->last_login = now();
        $user->save();
        Auth::login($user);
        return redirect()->route('get.dashboard');
    }

    public function dashboard()
    {
        return view('front.dashboard');
    }

    public function getProfile()
    {
        $user = auth()->user();
        return view('front.profile', compact('user'));
    }

    public function logout()
    {

        Toastr::success('Logout successfully');
        Auth::logout();
        return redirect()->route('get.login');
    }

    public function updateProfile(Request $request)
    {

        $user = auth()->user();
        $request->validate([
            'dealer_name' => 'required',
            'gst_no' => 'required',
            'full_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'pincode' => 'required|numeric|digits:6',
            'state' => 'required',
            'phone' => 'required|numeric|digits:10|unique:users,phone,' . $user->id,
        ]);

        $input = $request->all();
        $user->update([
            'dealership_name' => $input["dealer_name"],
            'gst_number' => $input["gst_no"],
            'name' => $input['full_name'],
            'address' => $input['address'],
            'city' => $input['city'],
            'zip' => $input['pincode'],
            'state' => $input['state'],
            'phone' => $input['phone'],
            'mobile' => $input['mobile'],
            'email' => $input['email'],
            'username' => $input['email'],
        ]);

        Toastr::success('Profile updated successfully');
        return redirect()->route('profile.get');
    }
}
