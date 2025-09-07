
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CropCycleApiController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Models\Farm;
use App\Models\Incident;

use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('crop-cycles', CropCycleApiController::class);
    Route::get('dashboard/yield-per-crop', [AnalyticsController::class,'yieldPerCrop']);
    Route::get('dashboard/monocropping', [AnalyticsController::class,'monocroppingFlags']);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/farms', fn() => Farm::all(['id','farm_name','owner','lat','lng','area_size']));
Route::get('/incidents', fn() => Incident::all(['id','incident_type','severity','lat','lng','date','notes']));


