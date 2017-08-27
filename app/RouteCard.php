<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Delivery;

class RouteCard extends Model
{
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
