<?php

namespace SmoDav\Controllers;

use App\Driver;
use App\Employee;
use App\HrEmployeeDesignation;
use App\HrEmployeesModel;
use App\Http\Controllers\Controller;
use App\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use SmoDav\Engine\PassportRepository;
use SmoDav\Models\WorkshopEmployee;
use SmoDav\Support\Constants;
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
     * @param \Illuminate\Http\Request $request
     *
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
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // return view('masters.employees.show', ['driver', Driver::findOrFail($id)]);
        switch ($id) {
            case 'drivers':
                return $this->getTableData(Constants::DRIVER);
                break;
            case 'workshop':
                return $this->getTableData(Constants::WORKSHOP);
                break;
            case 'employees':
                return $this->getTableData();
                break;
            default:
                break;
        }

        return view('masters.employees.show', ['driver' => Driver::findOrFail($id)]);
    }

    public function edit($id)
    {
        $employee = WorkshopEmployee::findOrFail($id);

        return view('masters.employees.edit')
            ->with('type', request('t', 'employees'))
            ->with('driver', Driver::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        //
        $driver = Driver::findOrFail($id);
        $driver->fill($request->all());

        foreach ($request->all() as $key => $item) {
            if ($key == '_token' || $key == '_method' || $key == 'updated_at' || $key == 'deleted_at') {
                continue;
            }
            $driver->{$key} = $item;

            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = \time() . '.' . $extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $driver->{$key} = $filename;
            }
        }

        $driver->save();

        session()->flash('flash_message', 'Driver successfully updated.');
        session()->flash('flash_status', 'success');

        return redirect()->route('super.employee.index');
    }

    public function destroy($id)
    {
        //
        $driver = Driver::findOrFail($id)->delete();

        session()->flash('flash_message', 'Driver successfully deleted.');
        session()->flash('flash_status', 'success');

        return redirect()->route('super.employee.index');
    }

    private function getTableData($type = null)
    {
        $fields = [
            'id', 'first_name', 'last_name', 'mobile_phone', 'identification_number', 'identification_type', 'email'
        ];

        switch ($type) {
            default:
            case Constants::DRIVER:
                $results = Driver::select($fields);
                break;
            case Constants::WORKSHOP:
                $results = WorkshopEmployee::select($fields);
                break;
        }

        return Datatables::of($results)
            ->addColumn('actions', function ($result) {
                return
                    '<a href="' . route('super.employee.show', $result->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>
                    <a href="' . route('super.employee.edit', $result->id) .
                    '" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                            ';
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
        try {
            \ini_set('max_execution_time', 3000);
            $employees = PassportRepository::getPayrollEmployees();
            $departments = Option::select(['option_key', 'option_value'])
                ->whereIn('option_key', [Option::PAYROLL_DEPARTMENT_DRIVER, Option::PAYROLL_WORKSHOP_DRIVER])
                ->get();

            $driver = $departments->where('option_key', Option::PAYROLL_DEPARTMENT_DRIVER)->first()->option_value;
            $workshop = $departments->where('option_key', Option::PAYROLL_WORKSHOP_DRIVER)->first()->option_value;

            unset($departments);

            $drivers = Driver::all(['identification_number'])->map(function ($employee) {
                return $employee->identification_number;
            })->toArray();

            $workshopEmployees = WorkshopEmployee::all(['identification_number'])->map(function ($employee) {
                return $employee->identification_number;
            })->toArray();

            $currentEmployees = \array_merge($drivers, $workshopEmployees);

            unset($drivers, $workshopEmployees);

            $employees = collect($employees)->groupBy('department_id');
            foreach ($employees as $key => $value) {
                foreach ($value as $employee) {
                    $employee = (array)$employee;

                    if (\in_array($employee['identification_number'], $currentEmployees)) {
                        continue;
                    }

                    switch ($key) {
                        case $driver:
                            Driver::create($employee);
                            continue;
                            break;
                        case $workshop:
                            WorkshopEmployee::create($employee);
                            continue;
                            break;
                        case $supervisor:
                            Supervisor::create($employee);
                            continue;
                            break;
                        default:
                            break;
                    }
                }
            }

            return redirect()->back()->with('success', 'Successfully imported employee information');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Please confirm the Payroll Integration is set up.');
        }
    }

    function HrEmployees()
    {
        $hremployees = HrEmployeesModel::with('designation')->get();
        $currentemployees = Employee::get();

        $fetched = false;

        if ($currentemployees->count() != $hremployees->count()) {
          $fetched =   $this->fetchFromHr();
        }

        return Response::json(["status"=>$fetched]);
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

            return true;


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
