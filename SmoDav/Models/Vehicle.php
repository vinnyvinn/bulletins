<?php

namespace SmoDav\Models;

use App\Driver;
use App\Support\Core;
use App\Truck;
use Illuminate\Database\Eloquent\Model;
use App\Contract;
use SmoDav\Models\LocalShunting\LSGatePass;
use SmoDav\Models\Model as VehicleModel;
use SmoDav\Models\LocalShunting\LSDelivery;

class Vehicle extends Model
{
    protected $fillable = [
        'plate_number', 'make_id', 'model_id', 'max_load', 'type', 'status', 'location', 'project_id', 'trailer_id',
        'driver_id', 'current_fuel', 'current_km', 'sub_contracted','contract_id'
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
        return $this->belongsTo(self::class, 'trailer_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function gatepass()
    {
        return $this->hasOne(GatePass::class);
    }

    public function lsgatepass()
    {
        return $this->hasOne(LSGatePass::class);
    }

    public function lsdelivery()
    {
        return $this->hasMany(LSDelivery::class);
    }

    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class);
    }
}
