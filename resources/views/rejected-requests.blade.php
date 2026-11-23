@extends('layouts.app')

@section('title', 'Rejected Requests')

@section('content')

    <h1>Rejected Requests</h1>

    <div style="background:white; padding:20px; border-radius:10px; margin-top:20px;">

        <table border="1" cellpadding="10" width="100%">

            <tr>
                <th>Lecturer</th>
                <th>Course Unit</th>
                <th>Class Size</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
            </tr>

            @forelse($rejectedRequests as $rejected)

                <tr>
                    <td>{{ $rejected->lecturer_name }}</td>
                    <td>{{ $rejected->course_unit }}</td>
                    <td>{{ $rejected->class_size }}</td>
                    <td>{{ $rejected->date }}</td>
                    <td>{{ $rejected->start_time }} - {{ $rejected->end_time }}</td>
                    <td>{{ $rejected->rejection_reason ?? '-' }}</td>
                </tr>

            @empty

                <tr>
                    <td colspan="6">No rejected requests found.</td>
                </tr>

            @endforelse

        </table>

    </div>

@endsection
