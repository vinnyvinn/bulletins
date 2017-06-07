<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CargoTypeRequest;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;

class CargoTypeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.cargo_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('workshop.cargo_type.create')
            ->with('classifications', CargoClassification::all(['id', 'name']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CargoTypeRequest $request)
    {
        CargoType::create($request->all());

        $route = 'workshop.cargo-type.index';

        if ($request->has('save_new')) {
            $route = 'workshop.cargo-type.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new cargo type.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CargoType  $cargoType
     * @return \Illuminate\Http\Response
     */
    public function show(CargoType $cargoType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CargoType  $cargoType
     * @return \Illuminate\Http\Response
     */
    public function edit(CargoType $cargoType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CargoType  $cargoType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CargoType $cargoType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CargoType  $cargoType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CargoType $cargoType)
    {
        //
    }

    private function getTableData()
    {
        $records = CargoType::with(['classification']);

        return Datatables::of($records)
            ->addColumn('class', function ($record) {
                return $record->classification->name;
            })
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
