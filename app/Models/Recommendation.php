<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['user_id','farm_id','crop_cycle_id','category','message','meta','source','status'];
    protected $casts = ['meta'=>'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function farm() { return $this->belongsTo(Farm::class); }
    public function cropCycle() { return $this->belongsTo(CropCycle::class); }
}
