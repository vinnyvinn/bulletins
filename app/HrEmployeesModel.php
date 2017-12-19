<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrEmployeesModel extends Model
{
    protected $connection = 'hr'; //connection to hr system
    protected $table = "persons";

}
