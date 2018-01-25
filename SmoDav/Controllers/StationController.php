<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Datatables;
use DB;
use SmoDav\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            //return Station::with('whstransferbatch','consumptionbatch','whsemast')->get();

            return $this->getTableData();
        }


        return view('workshop.stations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouseTransferBatches = DB::table('_etblWhseTransferBatches')
            ->select([
                'idWhseTransferBatch', 'cBatchNo', 'cBatchDescription'
            ])
            ->get();

        $journalBatches = DB::table('_etblInvJrBatches')
            ->select([
                'IDInvJrBatches', 'cInvJrNumber', 'cInvJrDescription'
            ])
            ->get();

        $warehouses = DB::table('WhseMst')
            ->where('WhseLink', '<>', 1)
            ->select([
                'WhseLink', 'Code', 'Name'
            ])
            ->get();
        return view('workshop.stations.create')
            ->with('journalBatches', $journalBatches)
            ->with('warehouseBatches', $warehouseTransferBatches)
            ->with('warehouses', $warehouses);
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
        Station::create($request->all());

        $route = 'workshop.stations.index';

        if ($request->has('save_new')) {
            $route = 'workshop.stations.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new station.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Station $station
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Station $station)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Station $station
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Station $station)
    {
        $warehouseTransferBatches = DB::table('_etblWhseTransferBatches')
            ->select([
                'idWhseTransferBatch', 'cBatchNo', 'cBatchDescription'
            ])
            ->get();

        $journalBatches = DB::table('_etblInvJrBatches')
            ->select([
                'IDInvJrBatches', 'cInvJrNumber', 'cInvJrDescription'
            ])
            ->get();

        $warehouses = DB::table('WhseMst')
            ->where('WhseLink', '<>', 1)
            ->select([
                'WhseLink', 'Code', 'Name'
            ])
            ->get();

        return view('workshop.stations.create')->with('station', $station)
            ->with('journalBatches', $journalBatches)
            ->with('warehouseBatches', $warehouseTransferBatches)
            ->with('warehouses', $warehouses);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Station $station
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Station $station)
    {
        $station->update($request->all());

        $route = 'workshop.stations.index';

        if ($request->has('save_new')) {
            $route = 'workshop.stations.create';
        }

        return redirect()->route($route)->with('success', 'Successfully edited station.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Station $station
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Station $station)
    {
        $station->delete();

        return redirect()->route('workshop.stations.index')->with('success', 'Successfully edited station.');
    }

    private function getTableData()
    {
        $records = Station::with('whstransferbatch', 'consumptionbatch', 'whsemast')->get();
        foreach ($records as $record) {
            $record->warehouse_name = ($record->whsemast) ? $record->whsemast['Name'] : 'Not Set';
            $record->warehouse_tranfer_batch_name = $record->whstransferbatch['cBatchNo'];
            $record->consumptionbatch_name = ($record->consumptionbatch) ? $record->consumptionbatch['cInvJrNumber'] : 'Not Set';
        }

        return Datatables::of($records)
            ->addColumn('actions', function ($record) {
                return '
                <a href="' . route('workshop.stations.edit', $record->id) .
                    '" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                ';
            })
            ->editColumn('created_at', function ($record) {
                return Carbon::parse($record->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
