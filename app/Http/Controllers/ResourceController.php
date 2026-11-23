<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $resources = Resource::all();

        $editResource = null;

        if($request->edit){
            $editResource = Resource::findOrFail($request->edit);
        }

        return view('resources', compact('resources', 'editResource'));
    }


    public function store(Request $request)
    {
        Resource::create([
            'resource_name' => $request->resource_name,
            'availability_status' => $request->availability_status
        ]);

        return redirect('/resources');
    }


    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $resource->update([
            'resource_name' => $request->resource_name,
            'availability_status' => $request->availability_status
        ]);

        return redirect('/resources');
    }


    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);

        $resource->delete();

        return redirect('/resources');
    }
}
