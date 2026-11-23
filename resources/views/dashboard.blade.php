@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <h1>Dashboard</h1>

    <p>
        Welcome to the Rule-Based Intelligent Laboratory Scheduling System.
    </p>


    <div style="display:flex; gap:20px; margin-top:30px;">


        <div style="background:white; padding:20px; width:200px; border-radius:10px;">
            <h3>Laboratories</h3>
            <h1>{{ $labCount }}</h1>
            <p>Total Labs</p>
        </div>


        <div style="background:white; padding:20px; width:200px; border-radius:10px;">
            <h3>Resources</h3>
            <h1>{{ $resourceCount }}</h1>
            <p>Available Resources</p>
        </div>


        <div style="background:white; padding:20px; width:200px; border-radius:10px;">
            <h3>Requests</h3>
            <h1>{{ $requestCount }}</h1>
            <p>Scheduling Requests</p>
        </div>


        <div style="background:white; padding:20px; width:200px; border-radius:10px;">
            <h3>Schedules</h3>
            <h1>{{ $scheduleCount }}</h1>
            <p>Generated Schedules</p>
        </div>


    </div>


@endsection
