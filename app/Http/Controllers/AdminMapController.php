<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Incident;
use Inertia\Inertia;

class AdminMapController extends Controller
{
    public function index()
    {
        $farms = Farm::select('id', 'name', 'lat', 'lng', 'user_id')->with('user')->get();
        $incidents = Incident::select('id', 'type', 'severity', 'gps_lat', 'gps_lng', 'date_reported', 'farm_id')->with('farm')->get();

        return Inertia::render('Admin/Map', [
            'farms' => $farms,
            'incidents' => $incidents,
        ]);
    }
}
