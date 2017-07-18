<?php

namespace SmoDav\Models;

use App\Contract;
use App\Driver;
use App\Route;
use App\Truck;
use SmoDav\Support\Constants;
use App\Fuel;
use SmoDav\Models\Delivery;
use App\User;

class Journey extends SmoDavModel
{
    protected $fillable = [
        'status', 'is_contract_related', 'contract_id', 'journey_type', 'job_date', 'truck_id', 'driver_id', 'ref_no',
        'route_id', 'job_description', 'enquiry_from', 'subcontracted', 'sub_company_name', 'sub_address_1',
        'sub_address_2', 'sub_address_3', 'sub_address_4', 'raw','user_id','closed_by','mileage_balance', 'station_id',
        'ignore_delivery_note'
    ];

    public function mileage()
    {
        return $this->hasOne(Mileage::class);
    }

    public function mileages()
    {
        return $this->hasMany(Mileage::class);
    }

    public function fuel()
    {
        return $this->hasOne(Fuel::class);
    }

    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function truck()
    {
        return $this->belongsTo(Vehicle::class, 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function scopePending($builder)
    {
        return $builder->where('status', Constants::STATUS_PENDING);
    }

    public function scopeOpen($builder)
    {
        return $builder->where('status', Constants::STATUS_APPROVED);
    }

    public function scopeClosed($builder)
    {
        return $builder->where('status', Constants::STATUS_CLOSED);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gatepass()
    {
        return $this->hasOne(GatePass::class);
    }
}
