<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmoDav\Factory\TruckFactory;

class Driver extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'payroll_number', 'avatar', 'identification_number', 'identification_type', 'first_name',
        'last_name', 'email', 'mobile_phone', 'dl_number',
    ];


    public function scopeUnassigned($query)
    {
        $assigned = TruckFactory::allAssignedDrivers()->toArray();

        return $query->whereNotIn('id', $assigned);
    }

    public function truck()
    {
        return $this->hasOne(Truck::class);
    }
}
