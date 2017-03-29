<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'plate_number', 'max_load', 'status', 'location', 'driver_id', 'project_id', 'contract_id'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
