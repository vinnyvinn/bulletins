<?php

namespace SmoDav\Controllers;

use App\Activity;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;
use Illuminate\Http\Request;
use SmoDav\Models\Make;
use SmoDav\Models\Model;

class ModelController extends Controller
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

        return view('workshop.models.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $makes = Make::orderBy('name')->get(['id', 'name']);

        return view('workshop.makes.create')->with('makes', $makes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $data = [
            'name' => $request->get('name'),
            'make_id' => $request->get('make_id'),
        ];

        return DB::transaction(function () use ($data, $request) {
            $create = Model::create($data);
//            SAGEUDF::addMakeUDF($request->get('name'));

            if ($create) {
                Activity::create([
                    'user_id' => Auth::id(),
                    'activity' => 'You have added ' . $request->get('name') . '  model'
                ]);

                return redirect()->route('workshop.model.index')->with('success', 'Model created successfully');
            }

            return redirect()->back()->with('error', 'Something went wrong, please try again');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Make  $make
     * @return \Illuminate\Http\Response
     */
    public function show(Make $make)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Make  $make
     * @return \Illuminate\Http\Response
     */
    public function edit(Make $make)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Make  $make
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Make $make)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Make  $make
     * @return \Illuminate\Http\Response
     */
    public function destroy(Make $make)
    {
        //
    }

    private function getTableData()
    {
        $make = Model::with(['make' => function ($builder) {
            return $builder->select(['id', 'name']);
        }])->select(['id', 'name', 'make_id', 'created_at']);

        return Datatables::of($make)
            ->addColumn('actions', function ($make) {
                return '<a href="' . route('workshop.make.show', $make->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a>';
            })
            ->editColumn('created_at', function ($make) {
                return Carbon::parse($make->created_at)->format('d F Y');
            })
            ->editColumn('make_id', function ($model) {
                return $model->make->name;
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }
}
