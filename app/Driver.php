<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmoDav\Factory\TruckFactory;
use SmoDav\Models\journey;
use SmoDav\Models\Vehicle;

class Driver extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'payroll_number', 'avatar', 'identification_number', 'identification_type', 'first_name',
        'last_name', 'email', 'mobile_phone', 'dl_number',
    ];

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeUnassigned($query)
    {
        $assigned = TruckFactory::allAssignedDrivers()->toArray();

        return $query->whereNotIn('id', $assigned);
    }

    /**
     * @return mixed
     */
    public function truck()
    {
        return $this->hasOne(Truck::class);
    }

    /**
     * @return mixed
     */
    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    /**
     * @return mixed
     */
    public function journey()
    {
        return $this->hasMany(Journey::class);
    }
}
