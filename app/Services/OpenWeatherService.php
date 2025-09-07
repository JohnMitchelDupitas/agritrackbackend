<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getWeatherByCoords($lat, $lon)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather";
        $response = Http::get($url, [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);
        return $response->json();
    }

    public function getForecastByCoords($lat, $lon)
    {
        $url = "https://api.openweathermap.org/data/2.5/forecast";
        $response = Http::get($url, [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);
        return $response->json();
    }

    public function getWeatherByCity($city)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather";
        $response = Http::get($url, [
            'q' => $city . ',PH',
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);
        return $response->json();
    }

    public function getDailyWeather($lat, $lon)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather";
        $response = Http::get($url, [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'temperature_c' => $data['main']['temp'] ?? null,
                'humidity_pct' => $data['main']['humidity'] ?? null,
                'rainfall_mm' => $data['rain']['1h'] ?? 0, // rainfall in last 1h if available
            ];
        }
        return null;
    }
}
