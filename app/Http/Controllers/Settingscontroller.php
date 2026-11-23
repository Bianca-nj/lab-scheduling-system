<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $username = session('staff_username');

        $user = DB::table('system_credentials')
            ->where('username', $username)
            ->first();

        if (!$user || $user->password !== $request->current_password) {
            return back()->with('error', 'Current password is incorrect.');
        }

        DB::table('system_credentials')
            ->where('username', $username)
            ->update(['password' => $request->new_password]);

        return back()->with('success', 'Password updated successfully.');
    }
}
