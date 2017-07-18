<?php

namespace SmoDav\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class GatePass extends Model
{
    protected $fillable = [
        'journey_id', 'user_id', 'narration', 'station_id', 'gatepass_date', 'cargo'
    ];

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
