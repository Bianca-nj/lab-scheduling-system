<!DOCTYPE html>
<html>
<head>
    <title>Schedule Suggestion</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
            padding:40px;
        }

        .container{
            background:white;
            width:600px;
            margin:auto;
            padding:30px;
            border-radius:10px;
        }

        button{
            padding:10px 20px;
            margin-right:10px;
        }
    </style>

</head>
<body>

<div class="container">

    <h2>Schedule Conflict Detected</h2>

    <p>
        The requested laboratory is already booked.
    </p>

    <hr>

    <h3>Suggested Alternative</h3>

    <p>
        <strong>Laboratory:</strong>
        {{ $lab->lab_name }}
    </p>

    <p>
        <strong>Date:</strong>
        {{ $requestRecord->date }}
    </p>

    <p>
        <strong>Suggested Time:</strong>
        {{ $suggestedStart }}
        -
        {{ $suggestedEnd }}
    </p>

    <form method="POST" action="/suggestion/accept-time">

        @csrf

        <input type="hidden" name="request_id"
               value="{{ $requestRecord->id }}">

        <input type="hidden" name="lab_id"
               value="{{ $lab->id }}">

        <input type="hidden" name="start_time"
               value="{{ $suggestedStart }}">

        <input type="hidden" name="end_time"
               value="{{ $suggestedEnd }}">

        <button type="submit">
            Accept Suggestion
        </button>

    </form>

    <br>

    <form method="POST" action="/suggestion/reject-time">

        @csrf

        <input type="hidden" name="request_id"
               value="{{ $requestRecord->id }}">

        <button type="submit">
            Reject Suggestion
        </button>

    </form>

</div>

</body>
</html>
