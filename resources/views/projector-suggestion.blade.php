<!DOCTYPE html>
<html>
<head>
    <title>Projector Suggestion</title>
    <style>
        body{font-family: Arial, sans-serif; background:#f5f5f5; padding:40px;}
        .container{background:white; width:600px; margin:auto; padding:30px; border-radius:10px;}
        button{padding:10px 20px; margin-right:10px;}
    </style>
</head>
<body>
<div class="container">

    <h2>No Fixed Projector Available</h2>

    <p>
        No laboratory with a capacity of at least {{ $requestRecord->class_size }}
        students has a built-in projector, but a portable projector is available
        and can be assigned to a suitable laboratory instead.
    </p>

    <hr>

    <p><strong>Laboratory:</strong> {{ $fallbackLab->lab_name }}</p>
    <p><strong>Resource:</strong> {{ $portableProjector->resource_name }}</p>

    <form method="POST" action="/suggestion/accept-projector">
        @csrf
        <input type="hidden" name="request_id" value="{{ $requestRecord->id }}">
        <input type="hidden" name="lab_id" value="{{ $fallbackLab->id }}">
        <input type="hidden" name="resource_id" value="{{ $portableProjector->id }}">
        <button type="submit">Accept Portable Projector</button>
    </form>

    <br>

    <form method="POST" action="/suggestion/decline-projector">
        @csrf
        <input type="hidden" name="request_id" value="{{ $requestRecord->id }}">
        <button type="submit">Decline And Reject</button>
    </form>

</div>
</body>
</html>
