<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkshopInspectionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SmoDav\Models\WorkshopInspectionCheckList;
use Yajra\Datatables\Datatables;

class WorkshopInspectionCheckListController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.workshop_inspection_checklist.index');
    }

    public function create()
    {
        return view('workshop.workshop_inspection_checklist.create');
    }

    public function store(WorkshopInspectionRequest $request)
    {
        WorkshopInspectionCheckList::create($request->all());
        $route = 'workshop.inspection.index';

        if ($request->has('save_new')) {
            $route = 'workshop.inspection.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new checklist item');
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
        $records = WorkshopInspectionCheckList::select(['id', 'name', 'created_at']);

        return Datatables::of($records)
            ->addColumn('actions', function ($record) {
                return '';
                return '<a href="' . route('workshop.job-type.show', $record->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            })
            ->editColumn('created_at', function ($record) {
                return Carbon::parse($record->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
