<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function yieldPerCrop()
    {
        $data = DB::table('crop_cycles as cc')
            ->join('farms as f','cc.farm_id','=','f.id')
            ->select('cc.crop_type', DB::raw('AVG(cc.yield_kg / NULLIF(f.size_ha,0)) as avg_kg_per_ha'))
            ->whereNotNull('cc.yield_kg')
            ->groupBy('cc.crop_type')
            ->get();
        return response()->json($data);
    }

    public function monocroppingFlags()
    {
        $rows = DB::table('crop_cycles')
            ->select('farm_id','crop_type', DB::raw('COUNT(*) as cycles'))
            ->groupBy('farm_id','crop_type')
            ->having('cycles','>=',3)
            ->get();
        return response()->json($rows);
    }
}
