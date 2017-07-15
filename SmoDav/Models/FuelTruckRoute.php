<?php

namespace SmoDav\Models;

use App\Route;

class FuelTruckRoute extends SmoDavModel
{
    protected $fillable = [
        'model_id', 'route_id', 'amount'
    ];

    public function model()
    {
        return $this->belongsTo(Model::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
