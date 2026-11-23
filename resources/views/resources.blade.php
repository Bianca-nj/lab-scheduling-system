@extends('layouts.app')

@section('title', 'Resources')

@section('content')

    <h1>Resources Management</h1>


    <!-- Add Resource -->

    <div style="background:white; padding:20px; border-radius:10px; margin-top:20px;">

        <h2>Add Resource</h2>

        <form method="POST" action="/resources/store">

            @csrf

            <label>Resource Name</label><br>
            <input type="text" name="resource_name" required>

            <br><br>


            <label>Availability Status</label><br>

            <select name="availability_status">

                <option value="available">
                    Available
                </option>

                <option value="unavailable">
                    Unavailable
                </option>

            </select>


            <br><br>


            <button type="submit">
                Save Resource
            </button>


        </form>

    </div>



    <!-- Edit Resource -->

    @if(isset($editResource))

        <div style="background:white; padding:20px; border-radius:10px; margin-top:20px;">

            <h2>Edit Resource</h2>


            <form method="POST" action="/resources/update/{{ $editResource->id }}">

                @csrf


                <label>Resource Name</label><br>

                <input type="text"
                       name="resource_name"
                       value="{{ $editResource->resource_name }}"
                       required>


                <br><br>

                <label>Availability Status</label><br>


                <select name="availability_status">


                    <option value="available"
                        {{ $editResource->availability_status == 'available' ? 'selected' : '' }}>
                        Available
                    </option>


                    <option value="unavailable"
                        {{ $editResource->availability_status == 'unavailable' ? 'selected' : '' }}>
                        Unavailable
                    </option>


                </select>


                <br><br>


                <button type="submit">
                    Update Resource
                </button>


            </form>

        </div>

    @endif




    <!-- Existing Resources -->

    <div style="background:white; padding:20px; border-radius:10px; margin-top:30px;">

        <h2>Existing Resources</h2>


        <table border="1" cellpadding="10" width="100%">


            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>



            @foreach($resources as $resource)


                <tr>

                    <td>
                        {{ $resource->resource_name }}
                    </td>

                    <td>
                        {{ $resource->availability_status }}
                    </td>


                    <td>

                        <a href="/resources?edit={{ $resource->id }}">
                            Edit
                        </a>
                        <br>

                        <a href="/resources/delete/{{ $resource->id }}"
                           onclick="return confirm('Delete this resource?')">
                            Delete
                        </a>


                    </td>


                </tr>


            @endforeach


        </table>


    </div>


@endsection
