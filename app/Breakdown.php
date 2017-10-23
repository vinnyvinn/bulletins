<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\JobCard;
use SmoDav\Models\Vehicle;

class Breakdown extends Model
{
    const APPROVED = 'Approved';
    const PENDING = 'Pending';
    const DECLINED = 'Declined';
    const CLOSED = 'Closed';

    protected $fillable = [
        'vehicle_id', 'breakdown_area_id', 'location', 'description', 'driver_id', 'incident_details',
        'vehicle_sent', 'attended_by', 'break_down_recovered', 'narration', 'breakdown_approved',
        'breakdown_approved_by', 'oims', 'status', 'raw'
    ];

    public function scopePending($builder)
    {
        return $builder->where('status', self::PENDING);
    }

    public function scopeApproved($builder)
    {
        return $builder->where('status', self::APPROVED);
    }

    public function scopeDisapproved($builder)
    {
        return $builder->where('status', self::DECLINED);
    }

    public function breakdownArea()
    {
        return $this->belongsTo(BreakdownArea::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function attendee()
    {
        return $this->belongsTo(Employee::class, 'attended_by');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function jobCards()
    {
        return $this->hasMany(JobCard::class);
    }
}
