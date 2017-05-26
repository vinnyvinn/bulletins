<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmoDav\Factory\TruckFactory;

class Driver extends Model
{
    use SoftDeletes;

     protected $fillable = [
         'name', 'national_id', 'dl_number', 'mobile'
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
