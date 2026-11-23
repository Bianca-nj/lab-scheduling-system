<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .sidebar {
            height: 100vh;
            width: 230px;
            background: #002147;
            color: white;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            color: #f4b400;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #f4b400;
            color: black;
        }

        .content {
            margin-left: 230px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 15px;
            border-bottom: 2px solid #ddd;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <h2>Lab Scheduler</h2>

    <a href="/dashboard">Dashboard</a>

    <a href="/laboratories">
        Laboratories
    </a>

    <a href="/resources">
        Resources
    </a>

    <a href="/schedules">
        Schedules
    </a>


    <a href="/settings">
        Settings
    </a>

    <a href="/logout">
        Logout
    </a>

</div>


<div class="content">

    <div class="header">
        <h3>Rule-Based Intelligent Laboratory Scheduling System</h3>
    </div>


    @yield('content')


</div>


</body>
</html>
