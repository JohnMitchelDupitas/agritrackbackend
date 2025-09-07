<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Farm;
use App\Models\WeatherLog;
use App\Services\OpenWeatherService;
use Carbon\Carbon;

class FetchDailyWeather extends Command
{
    protected $signature = 'weather:fetch-daily';
    protected $description = 'Fetch daily weather for all farms and store in weather_logs';

    public function handle(OpenWeatherService $weatherService)
    {
        $today = Carbon::today();

        $farms = Farm::whereNotNull('lat')->whereNotNull('lng')->get();

        foreach ($farms as $farm) {
            $data = $weatherService->getDailyWeather($farm->lat, $farm->lng);

            if ($data) {
                WeatherLog::updateOrCreate(
                    ['farm_id' => $farm->id, 'log_date' => $today],
                    [
                        'temperature_c' => $data['temperature_c'],
                        'humidity_pct' => $data['humidity_pct'],
                        'rainfall_mm' => $data['rainfall_mm'],
                        'source' => 'openweather'
                    ]
                );
                $this->info("Weather logged for farm {$farm->id} ({$farm->name})");
            }
        }
        return Command::SUCCESS;
    }
}
