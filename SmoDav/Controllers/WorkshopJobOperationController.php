<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopOperationRequest;
use Carbon\Carbon;
use SmoDav\Models\WorkshopJobOperation;
use Illuminate\Http\Request;
use SmoDav\Models\WorkshopJobType;
use Yajra\Datatables\Datatables;

class WorkshopJobOperationController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.workshop_job_operation.index');
    }

    public function create()
    {
        $types = WorkshopJobType::all(['id', 'name']);

        if (! count($types)) {
            return redirect()->route('workshop.job-type.index')
                ->with('error', 'Please create a job type first before creating a job operation');
        }

        return view('workshop.workshop_job_operation.crea   te')
            ->with('jobTypes', $types);
    }

    public function store(WorkshopOperationRequest $request)
    {
        WorkshopJobOperation::create($request->all());

        $route = 'workshop.job-operation.index';

        if ($request->has('save_new')) {
            $route = 'workshop.job-operation.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new operation');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    private function getTableData()
    {
        $records = WorkshopJobOperation::select(['id', 'workshop_job_type_id', 'name', 'created_at'])
            ->with(['type']);

        return Datatables::of($records)
            ->addColumn('job_type', function ($record) {
                return $record->type->name;
            })
            ->addColumn('actions', function ($record) {
                return '';
                return '<a href="' . route('workshop.job-type.show', $records->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            })
            ->editColumn('created_at', function ($record) {
                return Carbon::parse($record->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->removeColumn('workshop_job_type_id')
            ->removeColumn('type')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
