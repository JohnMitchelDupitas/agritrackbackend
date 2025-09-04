<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CropCycle extends Model
{
    protected $fillable = ['farm_id','crop_type','variety','season_label','date_planted','date_harvested','yield_kg','status'];

    public function farm() { return $this->belongsTo(Farm::class); }
    public function recommendations() { return $this->hasMany(Recommendation::class); }
}
