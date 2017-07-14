<?php

namespace SmoDav\Models;

use App\Driver;
use App\Support\Core;
use App\Truck;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'plate_number', 'make', 'model', 'max_load', 'type', 'status', 'location', 'project_id', 'trailer_id',
        'driver_id', 'current_fuel', 'current_km', 'sub_contracted'
    ];

    public function scopeTypeTruck($builder)
    {
        return $builder->where('type', Core::TRUCK);
    }

    public function scopeTypeTrailer($builder)
    {
        return $builder->where('type', Core::TRAILER);
    }

    public function scopeTypeVan($builder)
    {
        return $builder->where('type', Core::VAN);
    }

    public function scopeOther($builder)
    {
        return $builder->where('type', Core::OTHER);
    }

    public function trailer()
    {
        return $this->belongsTo(Vehicle::class, 'trailer_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
