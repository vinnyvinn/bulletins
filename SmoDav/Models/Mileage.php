<?php

namespace SmoDav\Models;

class Mileage extends SmoDavModel
{
    protected $fillable = [
        'journey_id', 'mileage_type', 'requested_amount', 'approved_amount', 'balance_amount', 'narration', 'raw',
        'status', 'standard_amount'
    ];

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }
}
