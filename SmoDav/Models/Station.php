<?php

namespace SmoDav\Models;

use App\User;

class Station extends SmoDavModel
{
    protected $fillable = ['name', 'location'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
