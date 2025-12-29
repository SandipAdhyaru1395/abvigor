<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Brian2694\Toastr\Facades\Toastr;

class SettingsController extends Controller
{
    public function index()
    {
        $adminEmails = Settings::get('admin_emails', '');
        return view('admin.settings.index', compact('adminEmails'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'admin_emails' => 'nullable|string',
        ], [
            'admin_emails.string' => 'Admin emails must be a valid string.',
        ]);

        // Validate email format if provided
        if ($request->filled('admin_emails')) {
            $emails = array_map('trim', explode(',', $request->admin_emails));
            foreach ($emails as $email) {
                if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Toastr::error('Invalid email format: ' . $email);
                    return redirect()->back()->withInput();
                }
            }
        }

        Settings::set('admin_emails', $request->admin_emails ?? '');

        Toastr::success('Settings updated successfully');
        
        if ($request->has('close') && $request->input('close') == 1) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.settings.index');
        }
    }
}
