<!DOCTYPE html>
<html>
<head>
    <title>Request Approved</title>
    <style>
        body{font-family: Arial, sans-serif; background:#f5f5f5; padding:40px;}
        .container{background:white; width:600px; margin:auto; padding:30px; border-radius:10px;}
        a.button{
            display:inline-block;
            padding:10px 20px;
            background:#002147;
            color:white;
            text-decoration:none;
            border-radius:4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Request Approved</h2>

    <hr>

    <p><strong>Lecturer:</strong> {{ $requestRecord->lecturer_name }}</p>
    <p><strong>Course:</strong> {{ $requestRecord->course_unit }}</p>
    <p><strong>Date:</strong> {{ $requestRecord->date }}</p>
    <p><strong>Laboratory:</strong> {{ $lab->lab_name }}</p>
    <p><strong>Resource:</strong> {{ $resource ? $resource->resource_name : 'None' }}</p>

    <br>
    <a class="button" href="/">OK</a>
</div>
</body>
</html>
