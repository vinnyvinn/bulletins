<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UDF extends Model
{
    //
    protected $table = 'u_d_fs';
    const ACTIVE = 1;
    const INACTIVE = 0;
    const TEXTAREA = 'Long Text';
    const TEXT = 'short text';
    const RADIO = 'radio button';
    const CHECKBOX = 'checkbox';
    const NUMBER = 'Integer';
    const DATE = 'Date';
    const DATETIME = 'DateTime';
    const SELECT = 'Select';
    const FILE = 'file';
    const MODULES = ['Drivers', 'Routes', 'Trailers', 'Trucks', 'Contracts'];
    const TABLES = [
        'Drivers' => 'drivers',
        'Routes' => 'routes',
        'Trailers' => 'trailers',
        'Trucks' => 'trucks',
        'Contracts' => 'contracts',
    ];
    protected $fillable = ['name', 'input_type', 'description', 'module', 'value', 'status','slug'];
}
