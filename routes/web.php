<?php

use App\Http\Controllers\AdminWeatherController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CropCycleController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\WeatherController;

Route::middleware(['auth'])->group(function () {
    Route::resource('crop-cycles', CropCycleController::class)->only(['index', 'create', 'store', 'show']);
});

Route::get('/export/crop-cycles', [App\Http\Controllers\CropCycleController::class, 'exportCsv'])->middleware('auth');

Route::resource('farms', FarmController::class);

Route::resource('incidents', IncidentController::class);

Route::middleware(['admin'])->group(function () {
    Route::get('/admin/map', [App\Http\Controllers\AdminMapController::class, 'index'])
        ->name('admin.map');
});

//weather
Route::get('/weather/farm', [WeatherController::class, 'showForFarm'])->middleware('auth');

Route::middleware(['auth','admin'])->group(function() {
    Route::get('/admin/weather/{province?}', [AdminWeatherController::class, 'byProvince'])->name('admin.weather');
});





// Recommendation routes
Route::put('/recommendations/{id}/mark-seen', [App\Http\Controllers\RecommendationController::class, 'markSeen'])->name('recommendations.mark-seen');
Route::put('/recommendations/{id}/mark-done', [App\Http\Controllers\RecommendationController::class, 'markDone'])->name('recommendations.mark-done');
Route::put('/recommendations/{id}/dismiss', [App\Http\Controllers\RecommendationController::class, 'dismiss'])->name('recommendations.dismiss');
Route::put('/recommendations/dismiss-all', [App\Http\Controllers\RecommendationController::class, 'dismissAll'])->name('recommendations.dismiss-all');


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
