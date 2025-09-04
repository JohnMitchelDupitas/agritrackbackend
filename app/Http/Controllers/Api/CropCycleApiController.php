<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCropCycleRequest;
use App\Models\CropCycle;
use Illuminate\Http\Request;

class CropCycleApiController extends Controller
{
    public function index() { return response()->json(CropCycle::with('farm')->latest()->paginate(20)); }

    public function store(StoreCropCycleRequest $request)
    {
        $cycle = CropCycle::create($request->validated());
        return response()->json($cycle, 201);
    }

    public function show($id) { return response()->json(CropCycle::with('farm','recommendations')->findOrFail($id)); }

    public function update(StoreCropCycleRequest $request, $id)
    {
        $cycle = CropCycle::findOrFail($id);
        $cycle->update($request->validated());
        return response()->json($cycle);
    }

    public function destroy($id)
    {
        CropCycle::destroy($id);
        return response()->json(null, 204);
    }
}
