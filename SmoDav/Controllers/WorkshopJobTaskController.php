<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopTaskRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SmoDav\Models\WorkshopJobOperation;
use SmoDav\Models\WorkshopJobTask;
use Yajra\Datatables\Datatables;

class WorkshopJobTaskController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.workshop_job_task.index');
    }

    public function create()
    {
        $operations = WorkshopJobOperation::all(['id', 'name']);

        if (! \count($operations)) {
            return redirect()->route('workshop.job-type.index')
                ->with('error', 'Please create a job operation first before creating a task');
        }

        return view('workshop.workshop_job_task.create')
            ->with('operations', $operations);
    }

    public function store(WorkshopTaskRequest $request)
    {
        WorkshopJobTask::create($request->all());
        $route = 'workshop.job-task.index';

        if ($request->has('save_new')) {
            $route = 'workshop.job-task.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new task');
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
        $records = WorkshopJobTask::select(['id', 'workshop_job_operation_id', 'name', 'created_at'])
            ->with('operation');

        return Datatables::of($records)
            ->addColumn('job_operation', function ($record) {
                return $record->operation->name;
            })
            ->addColumn('actions', function ($record) {
                return '';

                return '<a href="' . route('workshop.job-type.show', $record->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            })
            ->editColumn('created_at', function ($record) {
                return Carbon::parse($record->created_at)->format('d F Y');
            })
            ->removeColumn('operation')
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
