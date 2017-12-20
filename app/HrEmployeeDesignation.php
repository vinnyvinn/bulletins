<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrEmployeeDesignation extends Model
{
    //
    protected $connection = 'hr'; //connection to hr system
    protected $table = "tblDesignation";
}
