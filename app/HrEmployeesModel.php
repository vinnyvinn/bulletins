<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HrEmployeesModel extends Model
{
    protected $connection = 'hr'; //connection to hr system
    protected $table = "tblEmployee";

    public function designation()
    {
        return $this->belongsTo('App\HrEmployeeDesignation', 'Emp_Desig_Id', 'Desig_Id');
    }

}
