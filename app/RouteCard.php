<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Delivery;
use SmoDav\Models\Journey;

class RouteCard extends Model
{
    protected $fillable = [
        'journey_id', 'user_id', 'arrival_date', 'arrival_time', 'departure_date', 'departure_time', 'station_id'
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
