<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherLog extends Model
{
    protected $fillable = [
        'farm_id',
        'log_date',
        'rainfall_mm',
        'temperature_c',
        'humidity_pct',
        'source',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
