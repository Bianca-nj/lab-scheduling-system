<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;
use App\Models\Schedule;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $laboratories = Laboratory::all();

        $editLaboratory = null;

        if($request->edit){
            $editLaboratory = Laboratory::findOrFail($request->edit);
        }

        return view('laboratories', compact('laboratories','editLaboratory'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_name' => 'required|string',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:10',
            'projector' => 'required|in:Yes,No',
            'status' => 'required|in:available,unavailable',
        ], [
            'capacity.min' => 'Laboratory capacity must be at least 10 students.',
        ]);

        Laboratory::create($validated);

        return redirect('/laboratories');
    }

    public function edit($id)
    {
        $laboratory = Laboratory::findOrFail($id);

        return view('edit_laboratory', compact('laboratory'));
    }


    public function update(Request $request, $id)
    {
        $laboratory = Laboratory::findOrFail($id);

        $validated = $request->validate([
            'lab_name' => 'required|string',
            'location' => 'required|string',
            'capacity' => 'required|integer|min:10',
            'projector' => 'required|in:Yes,No',
            'status' => 'required|in:available,unavailable',
        ], [
            'capacity.min' => 'Laboratory capacity must be at least 10 students.',
        ]);

        $laboratory->update($validated);

        return redirect('/laboratories');
    }


    public function destroy($id)
    {
        $laboratory = Laboratory::findOrFail($id);

        $hasSchedules = Schedule::where('lab_id', $id)->exists();

        if ($hasSchedules) {
            return redirect('/laboratories')->with(
                'error',
                "Cannot delete \"{$laboratory->lab_name}\" because it has existing schedules linked to it."
            );
        }

        $laboratory->delete();

        return redirect('/laboratories');
    }
}