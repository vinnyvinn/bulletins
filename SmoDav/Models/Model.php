<?php

namespace SmoDav\Models;

class Model extends SmoDavModel
{
    protected $fillable = ['name', 'make_id'];

    public function make()
    {
        return $this->belongsTo(Make::class, 'make_id');
    }
}
