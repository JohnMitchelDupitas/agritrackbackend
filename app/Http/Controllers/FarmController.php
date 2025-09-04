<?php

// app/Http/Controllers/FarmController.php
namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class FarmController extends Controller
{
    public function destroy(Farm $farm)
    {
        $farm->delete();
        return redirect()->route('farms.index')->with('success', 'Farm deleted successfully.');
    }
    public function index()
    {
        return Inertia::render('Farms/Index', [
            'farms' => Farm::where('user_id', auth()->id())->paginate(10),
            'user' => auth()->user(), // <-- Pass the user here
            'recommendations' => auth()->user()?->recommendations()->latest()->take(5)->get(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Farms/Create');
    }

    public function edit(Farm $farm)
    {
        return Inertia::render('Farms/Edit', [
            'farm' => $farm
        ]);
    }

    public function update(Request $request, Farm $farm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'size_ha' => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|string|max:100',
            'gps_lat' => 'nullable|numeric',
            'gps_long' => 'nullable|numeric',
            'irrigation' => 'nullable|in:rainfed,irrigated',
            'boundary_geojson' => 'nullable|string',
        ]);
        $farm->update($request->all());
        return redirect()->route('farms.index')->with('success', 'Farm updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'size_ha' => 'nullable|numeric|min:0',
            'soil_type' => 'nullable|string|max:100',
            'gps_lat' => 'nullable|numeric',
            'gps_long' => 'nullable|numeric',
            'irrigation' => 'nullable|in:rainfed,irrigated',
            'boundary_geojson' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'User not authenticated');
        }
        // Use $user directly; Auth::user() should return User model
        $user->farms()->create($request->all());

        return redirect()->route('farms.index')->with('success', 'Farm added successfully.');
    }
}
