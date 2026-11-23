@extends('layouts.app')

@section('title', 'Schedules')

@section('content')

    <h1>Schedules</h1>

    <div style="background:white; padding:20px; border-radius:10px; margin-bottom:20px;">

        <form method="GET" action="/schedules">

            <label>Date</label>
            <input type="date"
                   name="schedule_date"
                   value="{{ request('schedule_date') }}">

            <br><br>

            <label>Status</label>

            <select name="status">

                <option value="">
                    All
                </option>

                <option value="Approved"
                    {{ request('status') == 'Approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="Rejected"
                    {{ request('status') == 'Rejected' ? 'selected' : '' }}>
                    Rejected
                </option>

            </select>

            <br><br>

            <button type="submit">
                Filter
            </button>

            <a href="/schedules">
                Clear Filters
            </a>

        </form>

    </div>

    <div style="background:white; padding:20px; border-radius:10px;">

        <table border="1" cellpadding="10" width="100%">

            <tr>
                <th>Lecturer</th>
                <th>Course Unit</th>
                <th>Laboratory</th>
                <th>Recurring</th>
                <th>Resource</th>
                <th>Date</th>
                <th>Time</th>
                <th>Decision</th>
                <th>Reason</th>
            </tr>

            @foreach($schedulingRequests as $schedulingRequest)

                <tr>

                    <td>
                        {{ $schedulingRequest->lecturer_name }}
                    </td>

                    <td>
                        {{ $schedulingRequest->course_unit }}
                    </td>

                    <td>
                        {{ $schedulingRequest->schedule?->laboratory?->lab_name ?? 'N/A' }}
                    </td>

                    <td>
                        @if($schedulingRequest->is_recurring == 'Yes')
                            <strong>Yes</strong>
                        @else
                            No
                        @endif
                    </td>

                    <td>
                        {{ $schedulingRequest->schedule?->resource?->resource_name ?? 'None' }}
                    </td>

                    <td>
                        {{ $schedulingRequest->date }}
                    </td>

                    <td>
                        {{ $schedulingRequest->start_time }}
                        -
                        {{ $schedulingRequest->end_time }}
                    </td>

                    <td>
                        {{ ucfirst($schedulingRequest->status) }}
                    </td>

                    <td>
                        {{ $schedulingRequest->schedule?->reason ?? $schedulingRequest->rejection_reason ?? '-' }}
                    </td>

                </tr>

            @endforeach

        </table>

    </div>

@endsection
