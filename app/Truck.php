<?php

namespace App;

use App\Support\Core;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number', 'max_load', 'status', 'location', 'driver_id', 'project_id', 'contract_id', 'make', 'model',
        'trailer_id'
    ];

    public function scopePreLoading($query)
    {
        return $query->where('location', Core::PRE_LOADING);
    }

    public function scopeAllocation($query)
    {
        return $query->where('location', Core::AWAITING_ALLOCATION);
    }

    public function scopeLoading($query)
    {
        return $query->where('location', Core::LOADING);
    }

    public function scopeEnroute($query)
    {
        return $query->where('location', Core::ENROUTE);
    }

    public function scopeOffloading($query)
    {
        return $query->where('location', Core::OFFLOADING);
    }

    public function scopeInYard($query)
    {
        return $query->where('location', Core::IN_YARD);
    }

    public function scopeIncident($query)
    {
        return $query->where('location', Core::INCIDENT);
    }

    public function scopeWorkshop($query)
    {
        return $query->where('location', Core::WORKSHOP);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function trailer()
    {
        return $this->belongsTo(Trailer::class);
    }
}