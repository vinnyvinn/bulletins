<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Smodav\Models\Journey;

class Fuel extends Model
{
    protected $fillable = [
      'journey_id','date','current_fuel','fuel_issued','status'
    ];

    public function journey(){
      return $this->hasMany(Journey::class);
    }
}
