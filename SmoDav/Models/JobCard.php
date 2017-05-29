<?php

namespace SmoDav\Models;

use Auth;

class JobCard extends SmoDavModel
{
    protected $fillable = [
        'service_type', 'vehicle_id', 'vehicle_type', 'vehicle_number', 'workshop_job_type_id', 'expected_completion',
        'time_in', 'job_description', 'current_km_reading', 'fuel_balance', 'has_trailer',
        'status', 'mechanic_findings', 'user_id', 'raw_data'
    ];

    public function type()
    {
        return $this->belongsTo(WorkshopJobType::class, 'workshop_job_type_id');
    }

    public function tasks()
    {
        return $this->hasMany(JobCardTask::class, 'job_card_id');
    }

    public function inspections()
    {
        return $this->hasMany(JobCardInspection::class, 'job_card_id');
    }

    public function scopeOwn($builder)
    {
        return $builder->where('user_id', Auth::id());
    }
}
