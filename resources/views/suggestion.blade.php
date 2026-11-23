<!DOCTYPE html>
<html>
<head>
    <title>Schedule Suggestion</title>
</head>
<body>

<h2>Schedule Suggestion</h2>

<p>
    Laboratory Assigned:
    <strong>{{ $lab->lab_name }}</strong>
</p>

<p>
    Requested Resource Not Available.
</p>

<form method="POST" action="/suggestion/accept">

    @csrf

    <input type="hidden" name="request_id" value="{{ $requestRecord->id }}">
    <input type="hidden" name="lab_id" value="{{ $lab->id }}">

    <button type="submit">
        Accept Without Resource
    </button>

</form>

<br>

<form method="POST" action="/suggestion/decline">

    @csrf

    <input type="hidden" name="request_id" value="{{ $requestRecord->id }}">

    <button type="submit">
        Decline And Reschedule
    </button>

</form>

</body>
</html>
