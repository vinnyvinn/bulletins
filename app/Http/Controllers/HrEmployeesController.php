<?php

namespace App\Http\Controllers;

use App\Employee;
use App\HrEmployeeDesignation;
use App\HrEmployeesModel;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\VehicleFactory;

class HrEmployeesController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hremployees = HrEmployeesModel::with('designation')->get();
        $employees = Employee::get();//get all current employees
        $allcreated = false;
        if ((int)count($hremployees) !== (int)count($employees)) {
            if ($this->deleteAllEmployees()) {
                foreach ($hremployees as $hremployee) {
                    $createemp = Employee::create([
                        'id' => $hremployee->Emp_Id,
                        'payroll_number' => $hremployee->Emp_Payroll_No,
                        'identification_number' => $hremployee->Emp_Payroll_No,
                        'identification_type' => 'National ID',
                        'first_name' => $hremployee->Emp_First_Name,
                        'last_name' => $hremployee->Emp_Last_Name,
                        'email' => $hremployee->Emp_Payroll_No,
                        'category' => $hremployee['designation']["Desig_Name"]

                    ]);
                    if ($createemp) {
                        $allcreated = true;
                    }

                }
            }

        }

        return Response::json([
            'status' => $allcreated

        ]);


    }

    public function deleteAllEmployees()
    {
        $alldeted = false;
        $allemployees = Employee::get();

        foreach ($allemployees as $employee) {
            if ($employee->delete()) {
                $alldeted = true;
            } else {
                $alldeted = false;
            };
        }
        if(count($allemployees === 0 )){
            $alldeted = true;
        }

        return $alldeted;

    }

}
