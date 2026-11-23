<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchedulingRequest;
use App\Models\Laboratory;
use App\Models\Resource;
use App\Models\Schedule;
use Carbon\Carbon;

class SchedulingRequestController extends Controller
{
    public function create()
    {
        return view('request-form');
    }

    public function rejected()
    {
        $rejectedRequests = SchedulingRequest::where('status', 'rejected')
            ->orderBy('date', 'desc')
            ->get();

        return view('rejected-requests', compact('rejectedRequests'));
    }

    
    private function labIsBusy($labId, $date, $startTime, $endTime)
    {
        $exactConflict = Schedule::where('lab_id', $labId)
            ->where('schedule_date', $date)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($exactConflict) {
            return true;
        }

        $targetDayOfWeek = Carbon::parse($date)->dayOfWeek;

        $recurringSchedules = Schedule::where('lab_id', $labId)
            ->where('decision', 'Approved')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->whereHas('request', function ($query) use ($date) {
                $query->where('is_recurring', 'Yes')
                    ->where('date', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('repeat_until')
                          ->orWhere('repeat_until', '>=', $date);
                    });
            })
            ->get();

        foreach ($recurringSchedules as $schedule) {
            if (Carbon::parse($schedule->schedule_date)->dayOfWeek === $targetDayOfWeek) {
                return true;
            }
        }

        return false;
    }

   
    private function resourceIsBusy($resourceId, $date, $startTime, $endTime)
    {
        $exactConflict = Schedule::where('resource_id', $resourceId)
            ->where('schedule_date', $date)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($exactConflict) {
            return true;
        }

        $targetDayOfWeek = Carbon::parse($date)->dayOfWeek;

        $recurringSchedules = Schedule::where('resource_id', $resourceId)
            ->where('decision', 'Approved')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->whereHas('request', function ($query) use ($date) {
                $query->where('is_recurring', 'Yes')
                    ->where('date', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('repeat_until')
                          ->orWhere('repeat_until', '>=', $date);
                    });
            })
            ->get();

        foreach ($recurringSchedules as $schedule) {
            if (Carbon::parse($schedule->schedule_date)->dayOfWeek === $targetDayOfWeek) {
                return true;
            }
        }

        return false;
    }

    /**
     * Finds the latest-ending busy window among the given labs on the given
     * date, taking into account both real schedule rows for that exact date
     * and recurring bookings projected forward onto this date. Returns
     * ['lab_id' => ..., 'end_time' => ...] or null if none are busy.
     */
    private function latestBusyWindow(array $labIds, $date)
    {
        $windows = collect();

        $exact = Schedule::whereIn('lab_id', $labIds)
            ->where('schedule_date', $date)
            ->get(['lab_id', 'start_time', 'end_time']);

        foreach ($exact as $schedule) {
            $windows->push([
                'lab_id' => $schedule->lab_id,
                'end_time' => $schedule->end_time,
            ]);
        }

        $targetDayOfWeek = Carbon::parse($date)->dayOfWeek;

        $recurring = Schedule::whereIn('lab_id', $labIds)
            ->where('decision', 'Approved')
            ->whereHas('request', function ($query) use ($date) {
                $query->where('is_recurring', 'Yes')
                    ->where('date', '<=', $date)
                    ->where(function ($q) use ($date) {
                        $q->whereNull('repeat_until')
                          ->orWhere('repeat_until', '>=', $date);
                    });
            })
            ->get(['lab_id', 'end_time', 'schedule_date']);

        foreach ($recurring as $schedule) {
            if (Carbon::parse($schedule->schedule_date)->dayOfWeek === $targetDayOfWeek) {
                $windows->push([
                    'lab_id' => $schedule->lab_id,
                    'end_time' => $schedule->end_time,
                ]);
            }
        }

        return $windows->sortByDesc('end_time')->first();
    }

    public function store(Request $request)
    {
        $requestRecord = SchedulingRequest::create([
            'lecturer_name' => $request->lecturer_name,
            'course_unit' => $request->course_unit,
            'class_size' => $request->class_size,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'requires_projector' => $request->requires_projector,
            'required_resource' => $request->required_resource,
            'is_recurring' => $request->is_recurring,
            'repeat_until' => $request->repeat_until,
            'status' => 'pending'
        ]);

        // --- Recurring continuation check ---
        // If this exact lecturer/course already has an approved recurring
        
        $recurringSchedule = Schedule::where('decision', 'Approved')
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->whereHas('request', function ($query) use ($request) {
                $query->where('lecturer_name', $request->lecturer_name)
                    ->where('course_unit', $request->course_unit)
                    ->where('is_recurring', 'Yes')
                    ->where(function ($q) use ($request) {
                        $q->whereNull('repeat_until')
                          ->orWhere('repeat_until', '>=', $request->date);
                    });
            })
            ->get()
            ->first(function ($schedule) use ($request) {
                return Carbon::parse($schedule->schedule_date)->dayOfWeek
                    === Carbon::parse($request->date)->dayOfWeek;
            });

        if ($recurringSchedule) {
            Schedule::create([
                'request_id' => $requestRecord->id,
                'lab_id' => $recurringSchedule->lab_id,
                'resource_id' => $recurringSchedule->resource_id,
                'schedule_date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'decision' => 'Approved',
                'reason' => 'Recurring booking - automatically approved based on a previous approval for the same day and time.'
            ]);

            $requestRecord->update(['status' => 'approved']);

            $lab = Laboratory::find($recurringSchedule->lab_id);
            $resource = $recurringSchedule->resource_id
                ? Resource::find($recurringSchedule->resource_id)
                : null;

            return view('request-approved', compact('lab', 'resource', 'requestRecord'));
        }

        $labQuery = Laboratory::where('status', 'available')
            ->where('capacity', '>=', $request->class_size);

        if ($request->requires_projector == 'Yes') {
            $labQuery->where('projector', 'Yes');
        }

        $labs = $labQuery->get();

        if ($labs->isEmpty()) {

            $capacityMatches = Laboratory::where('status', 'available')
                ->where('capacity', '>=', $request->class_size)
                ->exists();

            if (!$capacityMatches) {
                $reason = "No laboratory has a capacity of at least "
                    . $request->class_size . " students.";

                $requestRecord->update([
                    'status' => 'rejected',
                    'rejection_reason' => $reason
                ]);

                return view('no-lab-available', compact('requestRecord', 'reason'));
            }

            if ($request->requires_projector == 'Yes') {

                // No lab with a fixed projector fits, but check whether a
                // portable projector from the equipment pool can be assigned
                // to a capacity-matching lab instead of rejecting outright.
                $capacityLabs = Laboratory::where('status', 'available')
                    ->where('capacity', '>=', $request->class_size)
                    ->get();

                $fallbackLab = null;

                foreach ($capacityLabs as $candidateLab) {
                    if (!$this->labIsBusy($candidateLab->id, $request->date, $request->start_time, $request->end_time)) {
                        $fallbackLab = $candidateLab;
                        break;
                    }
                }

                $portableProjector = null;

                if ($fallbackLab) {
                    $portables = Resource::where('resource_name', 'like', 'Portable Projector%')
                        ->where('availability_status', 'available')
                        ->orderBy('id')
                        ->get();

                    foreach ($portables as $candidate) {
                        if (!$this->resourceIsBusy($candidate->id, $request->date, $request->start_time, $request->end_time)) {
                            $portableProjector = $candidate;
                            break;
                        }
                    }
                }

                if ($fallbackLab && $portableProjector) {
                    return view('projector-suggestion', compact(
                        'fallbackLab', 'portableProjector', 'requestRecord'
                    ));
                }

                $reason = "No laboratory with a capacity of at least "
                    . $request->class_size . " students also has a projector, "
                    . "and no portable projector is currently available.";

                $requestRecord->update([
                    'status' => 'rejected',
                    'rejection_reason' => $reason
                ]);

                return view('no-lab-available', compact('requestRecord', 'reason'));
            }

            $reason = "No suitable laboratory is currently available.";

            $requestRecord->update([
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);

            return view('no-lab-available', compact('requestRecord', 'reason'));
        }

        $lab = null;

        foreach ($labs as $candidateLab) {
            if (!$this->labIsBusy($candidateLab->id, $request->date, $request->start_time, $request->end_time)) {
                $lab = $candidateLab;
                break;
            }
        }

        if (!$lab) {

            $latestWindow = $this->latestBusyWindow($labs->pluck('id')->toArray(), $request->date);

            if ($latestWindow) {

                $suggestedStart = $latestWindow['end_time'];

                $duration = strtotime($request->end_time) - strtotime($request->start_time);

                $suggestedEnd = date('H:i', strtotime($suggestedStart) + $duration);

                $lab = Laboratory::find($latestWindow['lab_id']);

                if (!$lab) {
                    $reason = "All matching laboratories are booked and no alternative could be found.";

                    $requestRecord->update([
                        'status' => 'rejected',
                        'rejection_reason' => $reason
                    ]);

                    return view('no-lab-available', compact('requestRecord', 'reason'));
                }

                return view(
                    'time-suggestion',
                    compact('lab', 'requestRecord', 'suggestedStart', 'suggestedEnd')
                );
            }

            $reason = "All matching laboratories are booked and no alternative time could be found.";

            $requestRecord->update([
                'status' => 'rejected',
                'rejection_reason' => $reason
            ]);

            return view('no-lab-available', compact('requestRecord', 'reason'));
        }

        $resource = null;
        $needsResource = $request->required_resource != '' && $request->required_resource != 'None';

        if ($needsResource) {

            $resources = Resource::where(
                'resource_name', 'like', $request->required_resource . '%'
            )->orderBy('id')->get();

            foreach ($resources as $candidate) {
                if (!$this->resourceIsBusy($candidate->id, $request->date, $request->start_time, $request->end_time)) {
                    $resource = $candidate;
                    break;
                }
            }

            if (!$resource) {
                return view('suggestion', compact('lab', 'requestRecord'));
            }
        }

        $approvalReason = "Suitable laboratory found. Capacity requirement met. ";

        if ($request->requires_projector == 'Yes') {
            $approvalReason .= "Projector requirement satisfied. ";
        }

        $approvalReason .= $resource
            ? "Resource assigned: {$resource->resource_name}."
            : "No additional resource required.";

        Schedule::create([
            'request_id' => $requestRecord->id,
            'lab_id' => $lab->id,
            'resource_id' => $resource ? $resource->id : null,
            'schedule_date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'decision' => 'Approved',
            'reason' => $approvalReason
        ]);

        $requestRecord->update(['status' => 'approved']);

        return view('request-approved', compact('lab', 'resource', 'requestRecord'));
    }

    public function acceptRecommendation(Request $request)
    {
        $requestRecord = SchedulingRequest::findOrFail(
            $request->request_id
        );

        Schedule::create([
            'request_id' => $requestRecord->id,
            'lab_id' => $request->lab_id,
            'resource_id' => null,
            'schedule_date' => $requestRecord->date,
            'start_time' => $requestRecord->start_time,
            'end_time' => $requestRecord->end_time,
            'decision' => 'Approved',
            'reason' => 'Approved without requested resource'
        ]);

        $requestRecord->update([
            'status' => 'approved'
        ]);

        $lab = Laboratory::find($request->lab_id);
        $resource = null;

        return view('request-approved', compact('lab', 'resource', 'requestRecord'));
    }

    public function rejectRecommendation(Request $request)
    {
        $requestRecord = SchedulingRequest::findOrFail(
            $request->request_id
        );

        $reason = 'Lecturer declined to proceed without the requested resource.';

        $requestRecord->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        return redirect('/');
    }

    public function acceptTimeSuggestion(Request $request)
    {
        $requestRecord =
            SchedulingRequest::findOrFail(
                $request->request_id
            );

        Schedule::create([

            'request_id' => $requestRecord->id,

            'lab_id' => $request->lab_id,

            'resource_id' => null,

            'schedule_date' => $requestRecord->date,

            'start_time' => $request->start_time,

            'end_time' => $request->end_time,

            'decision' => 'Approved',

            'reason' =>
                'Alternative time suggested by system and accepted by lecturer'
        ]);

        $requestRecord->update([
            'status' => 'approved'
        ]);

        $lab = Laboratory::find($request->lab_id);
        $resource = null;

        return view('request-approved', compact('lab', 'resource', 'requestRecord'));
    }

    public function rejectTimeSuggestion(Request $request)
    {
        $requestRecord =
            SchedulingRequest::findOrFail(
                $request->request_id
            );

        $reason = 'Lecturer declined the suggested alternative time.';

        $requestRecord->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        return redirect('/');
    }

    public function acceptProjectorSuggestion(Request $request)
    {
        $requestRecord = SchedulingRequest::findOrFail(
            $request->request_id
        );

        Schedule::create([
            'request_id' => $requestRecord->id,
            'lab_id' => $request->lab_id,
            'resource_id' => $request->resource_id,
            'schedule_date' => $requestRecord->date,
            'start_time' => $requestRecord->start_time,
            'end_time' => $requestRecord->end_time,
            'decision' => 'Approved',
            'reason' => 'Portable projector assigned in place of a fixed projector.'
        ]);

        $requestRecord->update([
            'status' => 'approved'
        ]);

        $lab = Laboratory::find($request->lab_id);
        $resource = Resource::find($request->resource_id);

        return view('request-approved', compact('lab', 'resource', 'requestRecord'));
    }

    public function declineProjectorSuggestion(Request $request)
    {
        $requestRecord = SchedulingRequest::findOrFail(
            $request->request_id
        );

        $reason = 'Lecturer declined the portable projector alternative.';

        $requestRecord->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        return redirect('/');
    }
}