<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCropCycleRequest;
use App\Models\CropCycle;
use App\Models\Farm;
use App\Models\Recommendation;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CropCycleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cycles = CropCycle::with('farm.user')
            ->whereHas('farm', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(12);

        $recommendations = $user
            ? $user->recommendations()->with('farm')->where('status', 'new')->latest()->take(5)->get()
            : collect();

        return Inertia::render('CropCycles/Index', [
            'cycles' => $cycles,
            'recommendations' => $recommendations,
        ]);
    }

    public function create()
    {
    $user = Auth::user();
    $farms = $user ? $user->farms()->get() : collect();
        return Inertia::render('CropCycles/Create', ['farms' => $farms]);
    }

    public function store(StoreCropCycleRequest $request)
    {
        $data = $request->validated();
        $cycle = CropCycle::create($data);

        // Simple rule: last 3 cycles on farm same crop -> recommendation
        $last3 = CropCycle::where('farm_id', $cycle->farm_id)
            ->orderByDesc('date_planted')
            ->limit(3)
            ->pluck('crop_type');

        if ($last3->count() == 3 && $last3->unique()->count() == 1) {
            Recommendation::create([
                'user_id' => $cycle->farm->user_id,
                'farm_id' => $cycle->farm_id,
                'crop_cycle_id' => $cycle->id,
                'category' => 'rotation',
                'message' => "Detected consecutive plantings of {$cycle->crop_type}. Consider rotating crops.",
                'source' => 'rule'
            ]);
        }

        return redirect()->route('crop-cycles.index')->with('success', 'Crop cycle saved');
    }

    public function show(CropCycle $cropCycle)
    {
        $cropCycle->load('farm.user', 'recommendations');
        return Inertia::render('CropCycles/Show', ['cycle' => $cropCycle]);
    }   

    public function exportCsv()
{
    $rows = \DB::table('crop_cycles as cc')
            ->join('farms as f','cc.farm_id','=','f.id')
            ->join('users as u','f.user_id','=','u.id')
            ->select('u.name as farmer','f.name as farm','cc.crop_type','cc.date_planted','cc.date_harvested','cc.yield_kg','f.size_ha')
            ->get();

    $csv = "farmer,farm,crop_type,date_planted,date_harvested,yield_kg,size_ha\n";
    foreach($rows as $r){
        $csv .= "\"{$r->farmer}\",\"{$r->farm}\",\"{$r->crop_type}\",{$r->date_planted},{$r->date_harvested},{$r->yield_kg},{$r->size_ha}\n";
    }
    return response($csv, 200, ['Content-Type'=>'text/csv','Content-Disposition'=>'attachment; filename=crop_cycles.csv']);
}

}
