<?php
namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Inertia\Inertia;

class AdminWeatherController extends Controller
{
    protected $weather;

    public function __construct(OpenWeatherService $weather)
    {
        $this->weather = $weather;
    }

    public function byProvince($province = "Isabela")
    {
        // Example: Admin can input "Isabela,PH"
        $weather = $this->weather->getWeatherByCity($province);
        return Inertia::render('Admin/Weather', [
            'weather' => $weather,
            'province' => $province
        ]);
    }
}
