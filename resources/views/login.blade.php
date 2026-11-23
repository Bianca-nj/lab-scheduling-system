<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rule-Based Intelligent Laboratory Scheduling System</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f4f6f9;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .login-container{
            width:400px;
            background:white;
            padding:40px;
            border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.15);
        }

        .header{
            background:#003366;
            color:white;
            padding:15px;
            text-align:center;
            border-radius:8px;
            margin-bottom:25px;
        }

        h2{
            font-size:22px;
        }

        .form-group{
            margin-bottom:20px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        }

        input{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:5px;
        }

        .btn-login{
            width:100%;
            padding:12px;
            background:#F4B400;
            border:none;
            color:black;
            font-weight:bold;
            cursor:pointer;
            border-radius:5px;
        }

        .btn-login:hover{
            opacity:0.9;
        }

        .system-name{
            text-align:center;
            margin-bottom:20px;
            color:#003366;
            font-weight:bold;
        }

        .error{
            color:red;
            margin-bottom:15px;
        }
    </style>

</head>
<body>

<div class="login-container">

    <div class="header">
        <h2>Login</h2>
    </div>

    <div class="system-name">
        Rule-Based Intelligent Laboratory Scheduling System
    </div>

    @if(session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn-login">
            Login
        </button>

    </form>

</div>

</body>
</html>
