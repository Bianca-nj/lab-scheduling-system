@extends('layouts.app')

@section('title', 'Settings')

@section('content')

    <h1>Settings</h1>

    <div style="background:white; padding:20px; border-radius:10px; margin-top:20px; max-width:500px;">

        <h2>Change Password</h2>

        @if(session('success'))
            <div style="background:#e0ffe0; color:#070; padding:10px; border-radius:6px; margin-bottom:15px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#ffe0e0; color:#a00; padding:10px; border-radius:6px; margin-bottom:15px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#ffe0e0; color:#a00; padding:10px; border-radius:6px; margin-bottom:15px;">
                <ul style="margin-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/settings/password">

            @csrf

            <label>Current Password</label><br>
            <input type="password" name="current_password" required>
            <br><br>

            <label>New Password</label><br>
            <input type="password" name="new_password" required>
            <br><br>

            <label>Confirm New Password</label><br>
            <input type="password" name="new_password_confirmation" required>
            <br><br>

            <button type="submit">
                Update Password
            </button>

        </form>

    </div>

@endsection
