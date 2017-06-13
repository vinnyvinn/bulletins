<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Journey;

class Fuel extends Model
{
    protected $fillable = [
      'journey_id','date','current_fuel','fuel_requested','fuel_issued','fuel_total','narration','status'
    ];

    public function journey()
    {
      return $this->belongsTo(Journey::class);
    }
}
