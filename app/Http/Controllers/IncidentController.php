<?php

// app/Http/Controllers/IncidentController.php
namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Farm;
use App\Models\CropCycle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with(['farm', 'cropCycle'])
            ->whereHas('farm', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->paginate(10);

        return Inertia::render('Incidents/Index', [
            'incidents' => $incidents,
        ]);
    }

    public function create()
    {
        $farms = auth()->user()->farms()->with('cropCycles')->get();
        return Inertia::render('Incidents/Create', [
            'farms' => $farms,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'farm_id' => 'required|exists:farms,id',
            'crop_cycle_id' => 'nullable|exists:crop_cycles,id',
            'type' => 'required|string',
            'severity' => 'nullable|string',
            'impact_pct' => 'nullable|integer|min:0|max:100',
            'date_reported' => 'required|date',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048', // max 2MB
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('incidents', 'public');
        }

        Incident::create($data);

        return redirect()->route('incidents.index')->with('success', 'Incident logged.');
    }
}
