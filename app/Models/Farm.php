<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = ['user_id', 'name', 'size_ha', 'soil_type', 'irrigation', 'lat', 'lng', 'boundary_geojson'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cropCycles()
    {
        return $this->hasMany(CropCycle::class);
        
        
    }
    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}
