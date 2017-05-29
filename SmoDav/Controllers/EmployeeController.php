<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SmoDav\Engine\PassportRepository;
use SmoDav\Models\Employee;
use Yajra\Datatables\Facades\Datatables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }
        return view('masters.employees.index')
            ->with('type', request('t', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        switch ($id) {
            case 'drivers':
                return $this->getTableData(Employee::DRIVER);
                break;
            case 'workshop':
                return $this->getTableData(Employee::WORKSHOP);
                break;
            case 'employees':
                return $this->getTableData();
                break;
            default:
                break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }

    private function getTableData($type = null)
    {
        $results = Employee::when($type, function ($builder) use ($type) {
            return $builder->where('type', $type);
        })->select([
            'id', 'first_name', 'last_name', 'mobile_phone', 'identification_number', 'identification_type', 'email'
        ]);

        return Datatables::of($results)
            ->addColumn('actions', function ($result) {
                return '<a href="' . route('super.employee.show', $result->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            })
            ->editColumn('created_at', function ($result) {
                return Carbon::parse($result->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function importFromPayroll()
    {
        ini_set('max_execution_time', 3000);
        $employees = PassportRepository::getPayrollEmployees();
        $departments = Option::select(['option_key', 'option_value'])
            ->whereIn('option_key', [Option::PAYROLL_DEPARTMENT_DRIVER, Option::PAYROLL_WORKSHOP_DRIVER])
            ->get();

        $driver = $departments->where('option_key', Option::PAYROLL_DEPARTMENT_DRIVER)->first()->option_value;
        $workshop = $departments->where('option_key', Option::PAYROLL_WORKSHOP_DRIVER)->first()->option_value;

        $employeeTypes = [
            $driver => Employee::DRIVER,
            $workshop => Employee::WORKSHOP
        ];

        $currentEmployees = Employee::all(['identification_number'])->map(function ($employee) {
            return $employee->identification_number;
        })->toArray();

        $employees = collect($employees)->groupBy('department_id');
        foreach ($employees as $key => $value) {
            foreach ($value as $employee) {
                $employee = (array) $employee;

                if (in_array($employee['identification_number'], $currentEmployees)) {
                    continue;
                }

                $employee['type'] = $employeeTypes[$key];
                Employee::create($employee);
            }
        }

        return redirect()->back()->with('success', 'Successfully imported employee information');
    }
}
