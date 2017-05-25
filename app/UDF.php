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
    const SECTIONS = ['Drivers', 'Routes', 'Trailers', 'Trucks', 'Trips', 'Contracts'];
    const TABLES = [
        'Drivers'=>'drivers',
        'Routes'=>'routes',
        'Trailers'=>'trailers',
        'Trucks'=>'trucks',
        'Trips'=>'trips',
        'Contracts'=>'contracts',
    ];
    protected $fillable = ['name', 'input_type', 'description', 'section', 'status'];

}
