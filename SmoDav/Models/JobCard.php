<?php

namespace SmoDav\Models;

class JobCard extends SmoDavModel
{
    protected $fillable = [
        'service_type', 'vehicle_id', 'workshop_job_type_id', 'workshop_job_types', 'expected_completion',
        'time_in', 'job_description', 'current_km_reading', 'fuel_balance', 'has_trailer'
    ];

    public function type()
    {
        return $this->belongsTo(WorkshopJobType::class, 'workshop_job_type_id');
    }
}
