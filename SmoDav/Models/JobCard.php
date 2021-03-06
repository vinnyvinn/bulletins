<?php

namespace SmoDav\Models;

use App\Breakdown;
use App\ExternalService;
use App\JobCardProgress;
use App\JobCardQC;
use App\User;
use App\WorkshopGatepass;
use Auth;
use SmoDav\Support\Constants;

class JobCard extends SmoDavModel
{
    protected $fillable = [
        'service_type', 'vehicle_id', 'vehicle_type', 'vehicle_number', 'workshop_job_type_id', 'expected_completion',
        'time_in', 'job_description', 'current_km_reading', 'fuel_balance', 'has_trailer',
        'status', 'mechanic_findings', 'user_id', 'raw_data', 'breakdown_id', 'closing_remarks',
        'station_id'
    ];

    public function jobType()
    {
        return $this->belongsTo(WorkshopJobType::class, 'workshop_job_type_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function scopeOpen($builder)
    {
        return $builder->where('status', Constants::STATUS_APPROVED);
    }

    public function scopeClosed($builder)
    {
        return $builder->where('status', Constants::STATUS_CLOSED);
    }

    public function breakdown()
    {
        return $this->belongsTo(Breakdown::class);
    }

    public function progress()
    {
        return $this->hasMany(JobCardProgress::class);
    }

    public function qualityCheck()
    {
        return $this->hasOne(JobCardQC::class);
    }

    public function gatePass()
    {
        return $this->hasMany(WorkshopGatepass::class);
    }

    public function externalServices()
    {
        return $this->hasMany(ExternalService::class);
    }
}
