<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopJobTypeRequest;
use Carbon\Carbon;
use SmoDav\Models\WorkshopJobType;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class WorkshopJobTypeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.workshop_job_type.index');
    }

    public function create()
    {
        return view('workshop.workshop_job_type.create');
    }

    public function store(WorkshopJobTypeRequest $request)
    {
        WorkshopJobType::create($request->all());

        $route = 'workshop.job-type.index';

        if ($request->has('save_new')) {
            $route = 'workshop.job-type.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added job type');
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
        $make = WorkshopJobType::select(['id', 'service_type', 'name', 'created_at']);

        return Datatables::of($make)
            ->addColumn('actions', function ($make) {
                return '';
                return '<a href="' . route('workshop.job-type.show', $make->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            })
            ->editColumn('created_at', function ($make) {
                return Carbon::parse($make->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
