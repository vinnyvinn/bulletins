<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Response;
use Auth;
use SmoDav\Models\LocalShunting\LSEmployeeMileage;

class RKEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return Response::json([
        'employees' => Employee::all()
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $employee = Employee::create($data);

        return Response::json([
          'status' => 'success',
          'message' => 'Employee details successfully saved',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function unallocatedEmployees ()
    {
      return Response::json([
        'employees' => Employee::whereNull('contract_id','')->get()
      ]);
    }

    public function allocateEmployee(Request $request)
    {
      $data = $request->all();
      $allocatedEmployees = $data['allocatedEmployees'];

      foreach($allocatedEmployees as $allocatedEmployee) {
        $employee = Employee::findOrFail($allocatedEmployee['id']);
        $employee->contract_id = $data['contract_id'];
        $employee->update();
      }
      return Response::json([
        'status' => 'success',
        'message' => 'Employee(s) successfully allocated'
      ]);
    }

    public function create_employee_mileage($employee, $contract)
    {
      return Response::json([
        'employee' => Employee::findOrFail($employee),
        'mileages' => LSEmployeeMileage::where('employee_id', $employee)
            ->where('contract_id', $contract)
            ->with('user')
            ->get()
      ]);
    }
}
