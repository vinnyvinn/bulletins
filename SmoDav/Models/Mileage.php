<?php

namespace SmoDav\Models;

use App\User;

class Mileage extends SmoDavModel
{
    protected $fillable = [
        'journey_id', 'mileage_type', 'requested_amount', 'approved_amount', 'balance_amount', 'narration', 'raw',
        'status', 'standard_amount','top_up','top_up_amount','top_up_reason','user_id', 'station_id'
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
