@extends('layouts.app')

@section('title', 'Laboratories')

@section('content')

    <h1>Laboratories Management</h1>

    @if(session('error'))
        <div style="background:#ffe0e0; color:#a00; padding:15px; border-radius:8px; margin-top:20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background:#ffe0e0; color:#a00; padding:15px; border-radius:8px; margin-top:20px;">
            <ul style="margin-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; padding:20px; border-radius:10px; margin-top:20px;">

        <h2>Add Laboratory</h2>

        <form method="POST" action="/laboratories/store">

            @csrf

            <label>Laboratory Name</label><br>
            <input type="text" name="lab_name" value="{{ old('lab_name') }}" required>
            <br><br>

            <label>Location</label><br>
            <input type="text" name="location" value="{{ old('location') }}" required>
            <br><br>

            <label>Capacity (minimum 10)</label><br>
            <input type="number" name="capacity" min="10" value="{{ old('capacity') }}" required>
            <br><br>

            <label>Projector Available</label><br>

            <select name="projector">
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>

            <br><br>

            <label>Status</label><br>
            <select name="status">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>

            <br><br>

            <button type="submit">
                Save Laboratory
            </button>

        </form>

    </div>

    @if(isset($editLaboratory))

        <div style="background:white; padding:20px; border-radius:10px; margin-top:20px;">

            <h2>Edit Laboratory</h2>

            <form method="POST" action="/laboratories/update/{{ $editLaboratory->id }}">

                @csrf

                <label>Laboratory Name</label><br>
                <input type="text" name="lab_name" value="{{ $editLaboratory->lab_name }}" required>

                <br><br>

                <label>Location</label><br>
                <input type="text" name="location" value="{{ $editLaboratory->location }}" required>

                <br><br>

                <label>Capacity (minimum 10)</label><br>
                <input type="number" name="capacity" min="10" value="{{ $editLaboratory->capacity }}" required>

                <br><br>

                <label>Projector Available</label><br>

                <select name="projector">

                    <option value="Yes"
                        {{ $editLaboratory->projector == 'Yes' ? 'selected' : '' }}>
                        Yes
                    </option>

                    <option value="No"
                        {{ $editLaboratory->projector == 'No' ? 'selected' : '' }}>
                        No
                    </option>

                </select>

                <br><br>

                <label>Status</label><br>

                <select name="status">

                    <option value="available"
                        {{ $editLaboratory->status == 'available' ? 'selected' : '' }}>
                        Available
                    </option>

                    <option value="unavailable"
                        {{ $editLaboratory->status == 'unavailable' ? 'selected' : '' }}>
                        Unavailable
                    </option>

                </select>

                <br><br>

                <button type="submit">
                    Update Laboratory
                </button>

            </form>

        </div>

    @endif

    <div style="background:white; padding:20px; border-radius:10px; margin-top:30px;">

        <h2>Existing Laboratories</h2>

        <table border="1" cellpadding="10" width="100%">

            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Projector</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            @foreach($laboratories as $lab)

                <tr>
                    <td>{{ $lab->lab_name }}</td>
                    <td>{{ $lab->location }}</td>
                    <td>{{ $lab->capacity }}</td>
                    <td>{{ $lab->projector }}</td>
                    <td>{{ $lab->status }}</td>
                    <td>

                        <a href="/laboratories?edit={{ $lab->id }}">
                            Edit
                        </a> <br>

                        <a href="/laboratories/delete/{{ $lab->id }}"
                           onclick="return confirm('Delete this laboratory?')">
                            Delete
                        </a>

                    </td>
                </tr>

            @endforeach

        </table>

    </div>


@endsection