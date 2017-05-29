<?php

namespace SmoDav\Models;

class Employee extends SmoDavModel
{
    protected $fillable = [
        'type', 'payroll_number', 'avatar', 'identification_number', 'identification_type', 'first_name',
        'last_name', 'email', 'mobile_phone'
    ];

    const DRIVER = 'Driver';
    const EMPLOYEE = 'Employee';
    const WORKSHOP = 'Workshop Employee';

    public function scopeDriver($builder)
    {
        return $builder->where('type', self::DRIVER);
    }

    public function scopeEmployee($builder)
    {
        return $builder->where('type', self::EMPLOYEE);
    }

    public function scopeWorkshop($builder)
    {
        return $builder->where('type', self::WORKSHOP);
    }
}
