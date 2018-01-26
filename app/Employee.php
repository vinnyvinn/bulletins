<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'id',
        'payroll_number',
        'identification_number',
        'identification_type',
        'first_name',
        'last_name',
        'email',
        'mobile_phone',
        'category',
        'contract_id'
    ];
}
