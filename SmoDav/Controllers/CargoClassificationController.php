<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CargoClassificationRequest;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use SmoDav\Models\CargoClassification;

class CargoClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.cargo_classification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workshop.cargo_classification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoClassificationRequest $request)
    {
        CargoClassification::create($request->all());

        $route = 'workshop.cargo-classification.index';

        if ($request->has('save_new')) {
            $route = 'workshop.cargo-classification.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new cargo classification.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function getTableData()
    {
        $records = CargoClassification::select(['id', 'name', 'created_at']);

        return Datatables::of($records)
            ->addColumn('actions', function ($record) {
                return '';
            })
            ->editColumn('created_at', function ($record) {
                return Carbon::parse($record->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
