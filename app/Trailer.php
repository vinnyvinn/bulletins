<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trailer extends Model
{
//    use SoftDeletes;

    protected $guarded = [];

    public function scopeUnassigned($query)
    {
        return $query->whereNull('truck_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
