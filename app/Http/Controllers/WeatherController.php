<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Inertia\Inertia;

class WeatherController extends Controller
{
    protected $weather;

    public function __construct(OpenWeatherService $weather)
    {
        $this->weather = $weather;
    }

    public function showForFarm()
    {
        $farm = auth()->user()->farms()->first(); // first farm for demo
        if (!$farm || !$farm->lat || !$farm->lng) {
            return Inertia::render('Weather/Farm', [
                'weather' => null,
                'forecast' => null
            ]);
        }

        $weather = $this->weather->getWeatherByCoords($farm->lat, $farm->lng);
        $forecast = $this->weather->getForecastByCoords($farm->lat, $farm->lng);

        return Inertia::render('Weather/Farm', [
            'weather' => $weather,
            'forecast' => $forecast
        ]);
    }
}
