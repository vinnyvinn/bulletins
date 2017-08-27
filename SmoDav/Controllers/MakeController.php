<?php

namespace SmoDav\Controllers;

use App\Activity;
use App\Http\Controllers\Controller;
use App\SAGEUDF;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use SmoDav\Models\Make;
use Yajra\Datatables\Datatables;

class MakeController extends Controller
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

        return view('workshop.makes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workshop.makes.create');
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
        $this->validate($request, ['name' => 'required']);

        $data = [
            'name' => $request->get('name')
        ];

        return DB::transaction(function () use ($data, $request) {
            $create = Make::create($data);
            //            SAGEUDF::addMakeUDF($request->get('name'));

            if ($create) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'activity' => 'You have added ' . $request->get('name') . '  make'
                ]);

                return redirect()->route('workshop.make.index')->with('success', 'Make created successfully');
            }

            return redirect()->back()->with('error', 'Something went wrong, please try again');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Make $make
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Make $make)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Make $make
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Make $make)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Make                $make
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Make $make)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Make $make
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Make $make)
    {
        //
    }

    private function getTableData()
    {
        $make = Make::select(['id', 'name', 'created_at']);

        return Datatables::of($make)
            ->addColumn('actions', function ($make) {
                return '<a href="' . route('workshop.make.show', $make->id) .
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
