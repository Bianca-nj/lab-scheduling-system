<!DOCTYPE html>
<html>
<head>
    <title>No Laboratory Available</title>
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
    <h2>Request Rejected</h2>

    <p>{{ $reason }}</p>

    <hr>

    <p><strong>Lecturer:</strong> {{ $requestRecord->lecturer_name }}</p>
    <p><strong>Course:</strong> {{ $requestRecord->course_unit }}</p>
    <p><strong>Class Size:</strong> {{ $requestRecord->class_size }}</p>
    <p><strong>Date:</strong> {{ $requestRecord->date }}</p>

    <br>
    <a class="button" href="/">OK</a>
</div>
</body>
</html>
