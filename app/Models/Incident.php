<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id','crop_cycle_id','type','severity',
        'impact_pct','notes','photo_path','date_reported',
        'gps_lat','gps_lng'
    ];

    public function farm() { return $this->belongsTo(Farm::class); }
    public function cropCycle() { return $this->belongsTo(CropCycle::class); }
}
