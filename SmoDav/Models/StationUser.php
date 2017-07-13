<?php

namespace App;

use SmoDav\Models\SmoDavModel;

class StationUser extends SmoDavModel
{
    protected $fillable = ['station_id', 'user_id'];
}
