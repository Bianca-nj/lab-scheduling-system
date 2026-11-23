<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user = DB::table('system_credentials')
            ->where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if($user)
        {
            session([
                'staff_logged_in' => true,
                'staff_username' => $user->username,
            ]);

            return redirect('/dashboard');
        }

        return back()->with('error','Invalid Username or Password');
    }

    public function logout()
    {
        session()->flush();

        return redirect('/login');
    }
}
