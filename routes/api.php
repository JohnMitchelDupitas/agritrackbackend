
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CropCycleApiController;
use App\Http\Controllers\Api\AnalyticsController;

use Illuminate\Http\Request;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('crop-cycles', CropCycleApiController::class);
    Route::get('dashboard/yield-per-crop', [AnalyticsController::class,'yieldPerCrop']);
    Route::get('dashboard/monocropping', [AnalyticsController::class,'monocroppingFlags']);
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


