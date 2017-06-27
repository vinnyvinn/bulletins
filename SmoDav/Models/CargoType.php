<?php

namespace SmoDav\Models;

class CargoType extends SmoDavModel
{
    protected $fillable = ['name', 'cargo_classification_id'];

    public function classification()
    {
        return $this->belongsTo(CargoClassification::class, 'cargo_classification_id');
    }
}
