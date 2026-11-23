<?php

namespace App\Http\Controllers;

use App\Models\SchedulingRequest;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = SchedulingRequest::with(['schedule.laboratory', 'schedule.resource'])
            ->whereIn('status', ['approved', 'rejected']);

        if ($request->status) {
            $query->where('status', strtolower($request->status));
        }

        if ($request->schedule_date) {
            $query->where('date', $request->schedule_date);
        }

        $schedulingRequests = $query->orderBy('date', 'desc')->get();

        return view('schedules', compact('schedulingRequests'));
    }
}
