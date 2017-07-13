<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Journey;

class Fuel extends Model
{
    protected $fillable = [
      'journey_id','date','current_fuel','fuel_requested','fuel_issued','fuel_total','narration','previous_km',
      'previous_fuel','current_km','status','tank','pump','top_up','top_up_reason','top_up_quantity','user_id',
        'station_id'
    ];

    public function journey()
    {
      return $this->belongsTo(Journey::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
