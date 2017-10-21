<?php

namespace App;

use App\Support\Core;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmoDav\Models\Journey;

class Truck extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number', 'make', 'model', 'max_load',
        'status', 'location', 'trailer_id', 'project_id',
        'driver_id','current_km','current_fuel'
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

    public function trailer()
    {
        return $this->belongsTo(Trailer::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class);
    }
}
