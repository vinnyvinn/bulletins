<?php

namespace SmoDav\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopEmployee extends SmoDavModel
{
    use SoftDeletes;

    protected $fillable = [
        'payroll_number', 'avatar', 'identification_number', 'identification_type', 'first_name',
        'last_name', 'email', 'mobile_phone', 'dl_number',
    ];
}
