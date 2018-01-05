<?php
namespace App\traits;
use App\Employee;
use App\HrEmployeeDesignation;
use App\HrEmployeesModel;

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 20/12/2017
 * Time: 15:12
 */
trait LoadEmployees{

    function HrEmployees()
    {
        $hremployees = HrEmployeesModel::with('designation')->get();
        $currentemployees = Employee::get();

        if ($currentemployees->count() != $hremployees->count()) {
           // $this->fetchFromHr();
        }
    }

    function fetchFromHr()
    {
        try {
            $hremployees = HrEmployeesModel::with('designation')->get();
            $currentemployees_payroll = [];

            $currentemployees = Employee::all();
            foreach ($currentemployees as $currentemployee) {
                array_push($currentemployees_payroll, $currentemployee->payroll_number);
            }
            $hremployeespayroll = [];
            foreach ($hremployees as $hremployee) {
                array_push($hremployeespayroll, $hremployee->Emp_Payroll_No);
            }
            $deleteemailarrays = array_diff($currentemployees_payroll, $hremployeespayroll);
            $this->deleteDeletedHrAcconts($deleteemailarrays);  //delete old accounts here


            $aaddedpayrolls = array_diff($hremployeespayroll, $currentemployees_payroll);

            foreach ($aaddedpayrolls as $key => $aaddedpayroll) {
                //get specific account from hr with the details
                $hraccount = HrEmployeesModel::where('Emp_Payroll_No', '=', $aaddedpayroll)->with('designation')->first();
                $added = Employee::create([
                    'payroll_number' => $hraccount->Emp_Payroll_No,
                    'identification_number' => $hraccount->Emp_Payroll_No,
                    'email' => $hraccount->Emp_Payroll_No,
                    'mobile_phone' => $hraccount->Emp_Payroll_No,
                    'first_name' => $hraccount->Emp_First_Name,
                    'last_name' => $hraccount->Emp_Last_Name,
                    'category' => ($hraccount->designation) ? $hraccount->designation->Desig_Name : 'Not assigned',
                    'contract_id' => 1
                ]);
            }
            // $updateusers = array_intersect($hremployeesemail, $currentemployees_email);


        } catch (\Exception $ex) {
            return false;
        }
    }

    function saveDesignations()
    {
        $designations = HrEmployeeDesignation::get();
        foreach ($designations as $designation) {


        }
    }


    function UpdateUserAcccount($payrollno, $details)
    {
        $user = Employee::where('payroll_number', '=', $details->email)->get();
        if ($user) {
            //update user details but dont delete
            $employee = new Employee();
            $employee->payroll_number = "PAYROLL NU";
            $employee->identification_number = "PAYROLL NU";
            $employee->identification_type = "PAYROLL NU";
            $employee->identification_type = "National ID";
            $employee->first_name = "National ID";
            $employee->last_name = "Last Name";
            $employee->email = "Last Name";
            $employee->mobile_phone = "Last Name";
            $employee->category = "Last Name";
            $employee->contract_id = "Last Name";
            $employee->Save();
        }
    }

    function deleteDeletedHrAcconts($payrolls)
    {
        $deleted = true;
        foreach ($payrolls as $payroll) {
            $user = Employee::where('payroll_number', '=',$payroll)->first();
            $user->delete();
        }
        return $deleted;
    }
}