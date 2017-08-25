<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use SmoDav\Models\MileageType;
use Datatables;
use Illuminate\Http\Request;

class MileageTypeController extends Controller
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

        return view('workshop.mileage_type.index');
    }

    private function getTableData()
    {
        $make = MileageType::select(['id', 'name', 'created_at']);

        return Datatables::of($make)
            ->addColumn('actions', function ($make) {
                return '<a href="' . route('workshop.mileage.edit', $make->id) .
                    '" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></a>';
            })
            ->editColumn('created_at', function ($make) {
                return Carbon::parse($make->created_at)->format('d F Y');
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('workshop.mileage_type.create');
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
        $this->validate($request, ['name' => 'required|unique:mileage_types']);

        $data = [
            'name' => $request->get('name'),
            'slug' => \str_replace('-', '_', str_slug($request->get('name')))
        ];

        MileageType::create($data);

        \Schema::table('routes', function ($table) use ($data) {
            $table->float($data['slug'])->default(0);
        });

        return redirect()->route('workshop.mileage.index')->with('success', 'Mileage type created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $mileageTypeId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($mileageTypeId)
    {
        $mileage = MileageType::findOrFail($mileageTypeId);

        return view('workshop.mileage_type.create')->with('type', $mileage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Make                $make
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $mileageTypeId)
    {
        $this->validate($request, ['name' => 'required|unique:mileage_types,name,' . $mileageTypeId]);

        $mileage = MileageType::findOrFail($mileageTypeId);
        $mileage->update($request->all());

        return view('workshop.mileage_type.index')->with('success', 'Mileage type udpated successfully');
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
        Schema::table('routes', function ($table) {
            $table->dropColumn($data['slug']);
        });
    }
}
