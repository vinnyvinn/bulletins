<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option_key', 'option_value'];

    public $timestamps = false;

    const OPTION_ITEM_GROUP = 'spares_item_group';

    const PAYROLL_DEPARTMENT_DRIVER = 'payroll_driver_department';

    const PAYROLL_WORKSHOP_DRIVER = 'payroll_workshop_department';

    const PAYROLL_EMPLOYEES_DRIVER = 'payroll_employees_department';
}
