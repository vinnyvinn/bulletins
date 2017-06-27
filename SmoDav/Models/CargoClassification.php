<?php

namespace SmoDav\Models;

class CargoClassification extends SmoDavModel
{
    protected $fillable = ['name'];

    public function types()
    {
        return $this->hasMany(CargoType::class);
    }
}
