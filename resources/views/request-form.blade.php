<!DOCTYPE html>
<html>
<head>
    <title>Laboratory Scheduling Request</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px;
        }

        .container {
            background: white;
            width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
        }

        button {
            background: #002147;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

    </style>

</head>

<body>


<div class="container">

    <h1>Laboratory Scheduling Request</h1>


    <form method="POST" action="/request/store">

        @csrf


        <label>Lecturer Name</label><br>
        <input type="text" name="lecturer_name" required>

        <br><br>


        <label>Course Unit</label><br>
        <input type="text" name="course_unit" required>

        <br><br>


        <label>Class Size</label><br>
        <input type="number" name="class_size" required>

        <br><br>

        <label>Requires Projector?</label><br>

        <select name="requires_projector">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>

        <br><br>

        <label>Required Resource</label><br>

        <select name="required_resource">
            <option value="">None</option>
            <option value="HDMI">HDMI</option>
            <option value="VGA">VGA</option>
            <option value="Portable Projector">Portable Projector</option>
        </select>

        <br><br>

        <label>Date</label><br>
        <input type="date" name="date" required>

        <br><br>


        <label>Start Time</label><br>
        <input type="time" name="start_time" required>

        <br><br>


        <label>End Time</label><br>
        <input type="time" name="end_time" required>

        <br><br>

        <label>Recurring Schedule?</label><br>

        <select name="is_recurring">

            <option value="No">No</option>
            <option value="Yes">Yes</option>

        </select>

        <br><br>

        <label>Repeat Until</label><br>

        <input type="date" name="repeat_until">

        <br><br>

        <button type="submit">
            Submit Request
        </button>


    </form>


</div>


</body>
</html>
