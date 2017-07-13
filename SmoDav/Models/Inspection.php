<?php

namespace SmoDav\Models;

use App\User;

class Inspection extends SmoDavModel
{
    protected $fillable = [
        'journey_id', 'status', 'from_station', 'to_station', 'fields', 'inspector_id', 'supervisor_id',
        'inspectors_comments', 'supervisors_comments', 'suitable_for_loading', 'station_id'
    ];

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
