<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    public function list(Request $request)
    {

        return view('admin.user.list');
    }

    public function getUsers(Request $request)
    {

        $users = User::withTrashed()->orderBy('id', 'desc')->get();

        return DataTables::of($users)
            ->addColumn('created_at', fn($row) => $row->created_at->format('d/m/Y'))
            ->addColumn('last_login', function ($row) {
                return $row->last_login
                    ? Carbon::parse($row->last_login)->format('d/m/Y')
                    : null;
            })
            ->make(true);

    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        User::whereIn('id', $ids)->delete();

        return response()->json(['success' => true]);
    }

    public function restoreMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        User::withTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['success' => true]);
    }

    public function deactivateMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        User::whereIn('id', $ids)->update([
            'is_activated' => 0
        ]);

        return response()->json(['success' => true]);
    }

    public function activateMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        User::whereIn('id', $ids)->update([
            'is_activated' => 1
        ]);

        return response()->json(['success' => true]);
    }

    public function add(Request $request)
    {
        return view('admin.user.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required',
            'mobile' => 'required|unique:users,phone',
            'activated_at' => 'nullable|date_format:d/m/Y H:i',
        ], [
            'username.required' => 'Username is required',
            'email.required' => 'Email is required',
            'mobile.required' => 'Mobile No is required',
            'activated_at.date_format' => 'The activated at field must match the format DDMM/YYYY HH:MM.',
        ]);

        $input = $request->all();

        $input['password'] = Hash::make('123456');

        User::create([
            'dealership_name' => $input["dealership_name"],
            'gst_number' => $input["gst_no"],
            'name' => $input['full_name'],
            'address' => $input['address'],
            'city' => $input['city'],
            'zip' => $input['pincode'],
            'state' => $input['state'],
            'phone' => $input['mobile'],
            'mobile' => $input['phone'],
            'email' => $input['email'],
            'is_activated' => 1,
            'activated_at' => ($input['activated_at']) ? Carbon::createFromFormat('d/m/Y H:i', $input['activated_at'])->format('Y-m-d H:i') : null,
            'username' => $input['username'],
            'password' => $input['password'],
        ]);

        Toastr::success('Registered successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.user.list');
        } else {
            return redirect()->route('admin.user.add');
        }
    }

    public function view($userId)
    {
        $user = User::withTrashed()->find($userId);
        return view('admin.user.view', compact('user'));
    }

    public function edit($userId)
    {
        $user = User::withTrashed()->find($userId);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required',
            'mobile' => 'required|unique:users,phone,' . $request->user_id,
            'activated_at' => 'nullable|date_format:d/m/Y H:i',
        ], [
            'username.required' => 'Username is required',
            'email.required' => 'Email is required',
            'mobile.required' => 'Mobile No is required',
            'activated_at.date_format' => 'The activated at field must match the format DDMM/YYYY HH:MM.',
        ]);

        $input = $request->all();

        $input['password'] = Hash::make('123456');

        User::find($request->user_id)->update([
            'dealership_name' => $input["dealership_name"],
            'gst_number' => $input["gst_no"],
            'name' => $input['full_name'],
            'address' => $input['address'],
            'city' => $input['city'],
            'zip' => $input['pincode'],
            'state' => $input['state'],
            'phone' => $input['mobile'],
            'mobile' => $input['phone'],
            'email' => $input['email'],
            'is_activated' => 1,
            'activated_at' => ($input['activated_at']) ? Carbon::createFromFormat('d/m/Y H:i', $input['activated_at'])->format('Y-m-d H:i') : null,
            'username' => $input['username'],
            'password' => $input['password'],
        ]);

        Toastr::success('Updated successfully');

        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.user.list');
        } else {
            return redirect()->route('admin.user.edit', $request->user_id);
        }
    }

    public function delete($userId)
    {
        User::find($userId)->delete();

        Toastr::success('Deleted successfully');
        return redirect()->route('admin.user.list');
    }
}
