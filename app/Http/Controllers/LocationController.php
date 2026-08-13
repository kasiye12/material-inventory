<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    public function index()
    {
        return view('locations.index');
    }

    public function create()
    {
        return redirect()->route('locations.index');
    }

    public function getData()
    {
        $locations = Location::query();
        
        return DataTables::of($locations)
            ->addColumn('actions', function($loc) {
                return $loc->id;
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:locations,code',
            'type' => 'required|in:head_office,project,site,store',
        ]);

        Location::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Location created successfully!'
        ]);
    }

    public function show($id)
    {
        return response()->json(Location::findOrFail($id));
    }

    public function edit($id)
    {
        return response()->json(Location::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:locations,code,'.$id,
            'type' => 'required|in:head_office,project,site,store',
        ]);
        $location->update($request->all());

        return response()->json(['success' => true, 'message' => 'Location updated!']);
    }

    public function destroy($id)
    {
        Location::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Location deleted!']);
    }
}
